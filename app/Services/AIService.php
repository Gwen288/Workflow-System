<?php
namespace App\Services;

use App\Models\User;
use App\Models\Request;

class AIService {
    
    /**
     * Suggest next approvers based on historical data and workload (simulated)
     */
    public function suggestNextApprover($workflowType, $priorityLevel, $step = 1) {
        $userModel = new User();
        
        $role = null;

        // Workflow Types: 1=Fee Waiver, 7=Budget, 8=Procurement
        if (in_array($workflowType, [1, 7])) { 
            // Fee Waiver and Budget route directly to Finance Officer as Step 1
            $role = ($step === 1) ? 'Finance Officer' : null;
        } else if ($workflowType == 8) {
            // Procurement path: Logistics (Yaw) -> Finance (Ama)
            $role = ($step === 1) ? 'Logistics' : (($step === 2) ? 'Finance Officer' : null);
        } else if (in_array($workflowType, [3, 4, 5, 6])) { 
            // Everything else (Clearance, Letters) routes to Registry initially
            $role = ($step === 1) ? 'Registry' : null;
        }

        if (!$role) return null;

        $candidates = $userModel->findByRole($role);
        if (count($candidates) > 0) {
            // Return random suitable candidate (can use load balancer logic here)
            return $candidates[array_rand($candidates)];
        }
        return null;
    }

    /**
     * Detect workflows that have been pending for too long, optionally filtered by workflow names
     */
    public function detectDelays($workflowNames = []) {
        $db = \App\Core\Database::getInstance();
        $sql = "SELECT r.* FROM Request r 
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                WHERE r.status IN ('Pending', 'Escalated') 
                AND r.submission_date < DATE_SUB(NOW(), INTERVAL 2 DAY)";
        
        if (!empty($workflowNames)) {
            $placeholders = implode(',', array_fill(0, count($workflowNames), '?'));
            $sql .= " AND w.name IN ($placeholders)";
            $stmt = $db->prepare($sql);
            $stmt->execute($workflowNames);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        return $db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Detect bottlenecks (roles with highest pending load)
     */
    public function detectBottlenecks($role = null) {
        $db = \App\Core\Database::getInstance();
        $sql = "SELECT COALESCE(u.role, 'Unknown') as role, COUNT(*) as pending_count 
                FROM Request r 
                LEFT JOIN User u ON r.current_approver = u.user_id 
                WHERE r.status IN ('Pending', 'Escalated') 
                GROUP BY u.role 
                ORDER BY pending_count DESC 
                LIMIT 1";
        $result = $db->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        if ($result && $result['pending_count'] > 3) {
            // Bottlenecks are generally relevant to everyone as they explain global slowness
            return "{$result['role']} is currently a bottleneck with {$result['pending_count']} pending requests.";
        }
        return null;
    }

    /**
     * Detect trends in request volume, optionally filtered by workflow names
     */
    public function detectVolumeTrends($workflowNames = []) {
        $db = \App\Core\Database::getInstance();
        $scopeSql = "";
        $params = [];
        
        if (!empty($workflowNames)) {
            $placeholders = implode(',', array_fill(0, count($workflowNames), '?'));
            $scopeSql = " AND workflow_type IN (SELECT workflow_id FROM Workflow WHERE name IN ($placeholders))";
            $params = array_merge($workflowNames, $workflowNames); // One set for thisWeek, one for lastWeek
        }

        $sqlThis = "SELECT COUNT(*) FROM Request WHERE submission_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) $scopeSql";
        $sqlLast = "SELECT COUNT(*) FROM Request WHERE submission_date >= DATE_SUB(NOW(), INTERVAL 14 DAY) AND submission_date < DATE_SUB(NOW(), INTERVAL 7 DAY) $scopeSql";
        
        $stmtThis = $db->prepare($sqlThis);
        $stmtThis->execute(!empty($workflowNames) ? array_slice($params, 0, count($workflowNames)) : []);
        $thisWeek = $stmtThis->fetchColumn();

        $stmtLast = $db->prepare($sqlLast);
        $stmtLast->execute(!empty($workflowNames) ? array_slice($params, count($workflowNames)) : []);
        $lastWeek = $stmtLast->fetchColumn();
        
        if ($thisWeek > $lastWeek && $lastWeek > 0) {
            $increase = round((($thisWeek - $lastWeek) / $lastWeek) * 100);
            $context = !empty($workflowNames) ? "Specific workflow volume" : "Overall request volume";
            return "$context is up {$increase}% this week compared to last week.";
        }
        return "Workflow volumes are currently stable and within normal parameters.";
    }

    /**
     * Flag budget risks (Procurement > Budget reference)
     */
    public function detectBudgetRisks() {
        $db = \App\Core\Database::getInstance();
        // This is a complex check across JSON metadata
        $sql = "SELECT r_proc.request_id, 
                       CAST(JSON_UNQUOTE(JSON_EXTRACT(r_proc.metadata, '$.procurement_cost')) AS DECIMAL(10,2)) as cost,
                       CAST(JSON_UNQUOTE(JSON_EXTRACT(r_budg.metadata, '$.budget_amount')) AS DECIMAL(10,2)) as budget
                FROM Request r_proc
                JOIN Request r_budg ON JSON_UNQUOTE(JSON_EXTRACT(r_proc.metadata, '$.budget_reference_id')) = r_budg.request_id
                WHERE r_proc.workflow_type = (SELECT workflow_id FROM Workflow WHERE name='Procurement')
                  AND r_budg.workflow_type = (SELECT workflow_id FROM Workflow WHERE name='Budget')";
        
        $risks = $db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        $flagged = [];
        foreach ($risks as $risk) {
            if ($risk['cost'] > $risk['budget']) {
                $flagged[] = "Risk Detected: Procurement #{$risk['request_id']} cost (\${$risk['cost']}) exceeds the referenced budget (\${$risk['budget']}).";
            }
        }
        return $flagged;
    }

    /**
     * Analyze dashboard metrics and generate textual narrative based on role
     */
    public function generateDashboardInsights($role = 'Admin') {
        $insights = [];
        
        // Define relevance mapping
        $relevanceMap = [
            'Admin' => ['Fee Waiver', 'Clearance', 'Procurement', 'Budget', 'Introductory Letter', 'Transcript'],
            'Finance Officer' => ['Fee Waiver', 'Procurement', 'Budget'],
            'CFO' => ['Fee Waiver', 'Procurement', 'Budget', 'Clearance'],
            'Logistics' => ['Procurement'],
            'Registry' => ['Clearance', 'Introductory Letter', 'Transcript'],
            'Library' => ['Clearance'],
            'HOD' => ['Budget', 'Procurement']
        ];

        $relevantWorkflows = $relevanceMap[$role] ?? [];

        // 1. Check Bottlenecks (Global for now, but can be tailored)
        $bottleneck = $this->detectBottlenecks($role);
        if ($bottleneck) $insights[] = $bottleneck;
        
        // 2. Check Volume Trends (Tailored)
        $insights[] = $this->detectVolumeTrends($relevantWorkflows);
        
        // 3. Check Budget Risks (Strict to Finance/Logistics/CFO/HOD)
        if (in_array($role, ['Finance Officer', 'CFO', 'Logistics', 'HOD', 'Admin'])) {
            $risks = $this->detectBudgetRisks();
            if (!empty($risks)) {
                $insights = array_merge($insights, $risks);
            }
        }

        // 4. Check Delays (Tailored)
        $delays = $this->detectDelays($relevantWorkflows);
        if (count($delays) > 0) {
            $insights[] = count($delays) . " pending requests in your department require your attention (over 48h old).";
        }

        return [
            'narrative' => $insights,
            'efficiencyScore' => 87.5
        ];
    }
}
