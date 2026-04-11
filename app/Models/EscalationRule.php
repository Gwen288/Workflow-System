<?php
namespace App\Models;

use App\Core\Model;

class EscalationRule extends Model {
    protected $table = 'EscalationRule';
    protected $primaryKey = 'rule_id';

    public function getRulesForWorkflow($workflowId) {
        $sql = "SELECT * FROM {$this->table} WHERE workflow_type = ?";
        return $this->rawQuery($sql, [$workflowId]);
    }
}
