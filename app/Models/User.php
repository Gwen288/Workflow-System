<?php
namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected $table = 'User';
    protected $primaryKey = 'user_id';

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function findByRole($role) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }
}
