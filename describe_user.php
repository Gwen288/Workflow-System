<?php
require 'app/Core/Database.php';

// Define expected constants
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'workflow_system');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $db = \App\Core\Database::getInstance();
    $stmt = $db->query("DESCRIBE User");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
