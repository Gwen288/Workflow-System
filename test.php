<?php
require_once 'config/config.php';
require_once 'app/Core/Database.php';

$db = \App\Core\Database::getInstance();
$w = $db->query("SELECT * FROM Workflow")->fetchAll();
echo json_encode($w, JSON_PRETTY_PRINT);
