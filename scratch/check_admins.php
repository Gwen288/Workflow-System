<?php
require_once 'app/Core/Database.php';
use App\Core\Database;

$db = Database::getInstance();
$stmt = $db->query("SELECT user_id, name, email, role FROM User WHERE role = 'Admin'");
$admins = $stmt->fetchAll();

if (empty($admins)) {
    echo "No Admin found.\n";
} else {
    print_r($admins);
}
