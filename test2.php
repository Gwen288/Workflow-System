<?php
require_once 'config/config.php';
require_once 'app/Core/Database.php';

$db = \App\Core\Database::getInstance();
$table = $db->query("SHOW CREATE TABLE Request")->fetchColumn(1);
echo "SCHEMA:\n";
echo $table;
