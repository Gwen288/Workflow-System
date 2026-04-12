<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Request;
use App\Models\AuditLog;

class AuditController extends Controller {
    public function __construct() {
        if (!auth()) {
            $this->redirect('/login');
        }
    }

    public function index() {
        if (auth_user()['role'] === 'Student') {
            return $this->redirect('/dashboard');
        }
        $search = $_GET['q'] ?? '';
        
        $requestModel = new Request();
        // Optimized query using correlated subquery for latest action
        $sql = "SELECT r.request_id, w.name as workflow_name, u1.name as submitter_name, 
                       r.submission_date, r.status, dp.action as last_action_type, dp.comment as last_action_comment
                FROM Request r
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                JOIN User u1 ON r.submitted_by = u1.user_id
                LEFT JOIN AuditLog dp ON dp.log_id = (
                    SELECT MAX(log_id) FROM AuditLog WHERE request_id = r.request_id
                )
                WHERE 1=1 ";
        
        $params = [];

        if (auth_user()['role'] === 'HOD') {
            $sql .= " AND r.submitted_by = ? ";
            $params[] = auth();
        }

        if (in_array(auth_user()['role'], ['Finance Officer', 'CFO'])) {
            $sql .= " AND w.name != 'Introductory Letter' ";
        }

        if (auth_user()['role'] === 'Library') {
            $sql .= " AND w.name = 'Clearance' ";
        }

        if (auth_user()['role'] === 'Logistics') {
            $sql .= " AND w.name = 'Procurement' ";
        }

        if (auth_user()['role'] === 'Registry') {
            $sql .= " AND w.name IN ('Clearance', 'Introductory Letter', 'English Proficiency Letter', 'Transcript') ";
        }
        
        if (!empty($search)) {
            $sql .= " AND (w.name LIKE ? OR u1.name LIKE ? OR r.request_id LIKE ? OR r.status LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        
        $sql .= " ORDER BY r.submission_date DESC LIMIT 50";
        
        $requests = $requestModel->rawQuery($sql, $params);
        
        $this->view('audit/index', [
            'requests' => $requests,
            'search' => $search
        ]);
    }

    public function search() {
        header('Content-Type: application/json');
        $query = $_GET['q'] ?? '';
        $workflowType = $_GET['type'] ?? '';
        $status = $_GET['status'] ?? '';

        $requestModel = new Request();
        $sql = "SELECT r.request_id, w.name as workflow_name, u1.name as submitter_name, 
                       r.submission_date, r.status, dp.action as last_action_type, dp.comment as last_action_comment
                FROM Request r
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                JOIN User u1 ON r.submitted_by = u1.user_id
                LEFT JOIN AuditLog dp ON dp.log_id = (
                    SELECT MAX(log_id) FROM AuditLog WHERE request_id = r.request_id
                )
                WHERE 1=1 ";
        
        $params = [];

        if (auth_user()['role'] === 'HOD') {
            $sql .= " AND r.submitted_by = ? ";
            $params[] = auth();
        }

        if (in_array(auth_user()['role'], ['Finance Officer', 'CFO'])) {
            $sql .= " AND w.name != 'Introductory Letter' ";
        }

        if (auth_user()['role'] === 'Library') {
            $sql .= " AND w.name = 'Clearance' ";
        }

        if (auth_user()['role'] === 'Logistics') {
            $sql .= " AND w.name = 'Procurement' ";
        }

        if (auth_user()['role'] === 'Registry') {
            $sql .= " AND w.name IN ('Clearance', 'Introductory Letter', 'English Proficiency Letter', 'Transcript') ";
        }
        
        if (!empty($query)) {
            $sql .= " AND (w.name LIKE ? OR u1.name LIKE ? OR r.request_id LIKE ? OR r.status LIKE ?)";
            $searchTerm = '%' . $query . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($workflowType)) {
            $sql .= " AND w.name = ? ";
            $params[] = $workflowType;
        }

        if (!empty($status)) {
            $sql .= " AND r.status = ? ";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY r.submission_date DESC LIMIT 50";
        
        $requests = $requestModel->rawQuery($sql, $params);
        echo json_encode($requests);
    }
}
