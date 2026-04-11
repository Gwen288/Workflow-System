<?php
namespace App\Models;

use App\Core\Model;

class AuditLog extends Model {
    protected $table = 'AuditLog';
    protected $primaryKey = 'log_id';

    public function getLogsForRequest($requestId) {
        $sql = "SELECT a.*, u.name as user_name 
                FROM {$this->table} a
                JOIN User u ON a.performed_by = u.user_id
                WHERE a.request_id = ?
                ORDER BY a.timestamp ASC";
        return $this->rawQuery($sql, [$requestId]);
    }
}
