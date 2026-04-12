<?php
namespace App\Models;

use App\Core\Model;

class Request extends Model {
    protected $table = 'Request';
    protected $primaryKey = 'request_id';

    public function getWithDetails() {
        $sql = "SELECT r.*, w.name as workflow_name, u1.name as submitter_name, u2.name as approver_name
                FROM {$this->table} r
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                JOIN User u1 ON r.submitted_by = u1.user_id
                LEFT JOIN User u2 ON r.current_approver = u2.user_id
                ORDER BY r.submission_date DESC";
        return $this->rawQuery($sql);
    }

    public function getPendingForUser($userId) {
        $sql = "SELECT r.*, w.name as workflow_name, u.name as submitter_name
                FROM {$this->table} r
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                JOIN User u ON r.submitted_by = u.user_id
                WHERE r.current_approver = ? AND r.status IN ('Pending', 'Escalated')";
        return $this->rawQuery($sql, [$userId]);
    }

    public function getSubmittedByUser($userId) {
        $sql = "SELECT r.*, w.name as workflow_name, u1.name as submitter_name, u2.name as approver_name
                FROM {$this->table} r
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                JOIN User u1 ON r.submitted_by = u1.user_id
                LEFT JOIN User u2 ON r.current_approver = u2.user_id
                WHERE r.submitted_by = ?
                ORDER BY r.submission_date DESC";
        return $this->rawQuery($sql, [$userId]);
    }

    public function getApprovedBudgetsForUser($userId) {
        $sql = "SELECT r.*, w.name as workflow_name, u.department 
                FROM {$this->table} r
                JOIN Workflow w ON r.workflow_type = w.workflow_id
                JOIN User u ON r.submitted_by = u.user_id
                WHERE w.name = 'Budget' 
                  AND r.status = 'Approved' 
                  AND r.submitted_by = ?
                ORDER BY r.submission_date DESC";
        return $this->rawQuery($sql, [$userId]);
    }
}
