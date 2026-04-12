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
        // Custom query to fetch requests with their last audit action
        // For simplicity, we just fetch all and filter in PHP, or do a comprehensive SQL.
        // A comprehensive SQL:
        $sql = "SELECT r.request_id, w.name as workflow_name, u1.name as submitter_name, 
                       r.submission_date, r.status, dp.action as last_action_type, dp.comment as last_action_comment
                FROM Request r
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                JOIN User u1 ON r.submitted_by = u1.user_id
                LEFT JOIN (
                    SELECT a.request_id, a.action, a.comment
                    FROM AuditLog a
                    INNER JOIN (
                        SELECT request_id, MAX(timestamp) as max_ts
                        FROM AuditLog
                        GROUP BY request_id
                    ) b ON a.request_id = b.request_id AND a.timestamp = b.max_ts
                ) dp ON r.request_id = dp.request_id
                WHERE 1=1 ";
        
        $params = [];

        if (auth_user()['role'] === 'HOD') {
            $sql .= " AND r.submitted_by = ? ";
            $params[] = auth();
        }
        
        if (!empty($search)) {
            $sql .= " AND (w.name LIKE ? OR u1.name LIKE ? OR r.request_id LIKE ? OR r.status LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        
        $sql .= " ORDER BY r.submission_date DESC";
        
        $requests = $requestModel->rawQuery($sql, $params);
        
        $this->view('audit/index', [
            'requests' => $requests,
            'search' => $search
        ]);
    }
}
