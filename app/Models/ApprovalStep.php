<?php
namespace App\Models;

use App\Core\Model;

class ApprovalStep extends Model {
    protected $table = 'ApprovalStep';
    protected $primaryKey = 'step_id';

    public function getStepsForRequest($requestId) {
        $sql = "SELECT * FROM {$this->table} WHERE request_id = ? ORDER BY timestamp ASC";
        return $this->rawQuery($sql, [$requestId]);
    }
}
