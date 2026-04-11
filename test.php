<?php
require_once 'config/config.php';
require_once 'app/Core/Database.php';

$db = \App\Core\Database::getInstance();
$users = $db->query("SELECT user_id, name, role FROM User")->fetchAll();
echo "USERS\n";
print_r($users);

$reqs = $db->query("SELECT request_id, submitted_by, workflow_type, status, current_approver FROM Request")->fetchAll();
echo "REQUESTS\n";
print_r($reqs);
