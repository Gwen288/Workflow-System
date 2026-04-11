<?php
require_once 'config/config.php';
require_once 'app/Core/Database.php';
$db = \App\Core\Database::getInstance();
$db->query("UPDATE Request SET status='Escalated' WHERE status=''");
