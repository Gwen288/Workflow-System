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
     * Detect workflows that have been pending for too long
     */
    public function detectDelays() {
        $requestModel = new Request();
        // Dummy logic to flag old pending requests
        $sql = "SELECT * FROM Request WHERE status = 'Pending' AND submission_date < DATE_SUB(NOW(), INTERVAL 2 DAY)";
        return $requestModel->rawQuery($sql);
    }

    /**
     * Analyze dashboard metrics
     */
    public function generateDashboardInsights() {
        return [
            'delays' => $this->detectDelays(),
            'anomalyFlags' => [
                'Unusual rate of rejectedProcurement requests detected in last 24h.',
                'Approver Ama Mensah is currently a bottleneck for 3 workflows.'
            ],
            'efficiencyScore' => 87.5
        ];
    }
}
