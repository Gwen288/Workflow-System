<?php
require_once 'config/config.php';
require_once 'app/Core/Database.php';

$db = \App\Core\Database::getInstance();
$db->query("INSERT INTO Workflow (name, status) VALUES ('Budget', 'Active'), ('Procurement', 'Active') ON DUPLICATE KEY UPDATE status='Active'");
echo "Inserted Budget and Procurement into Workflow.";
