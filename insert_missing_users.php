<?php
require_once __DIR__ . '/app/Core/Database.php';

if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', 'workflow_system');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

try {
    $db = \App\Core\Database::getInstance();
    $hash = password_hash('password123', PASSWORD_DEFAULT);
    
    // Insert missing users
    $sql = "INSERT INTO User (name, role, department, email, password) VALUES
        ('Dr. Jane Doe', 'HOD', 'Computer Science', 'jane.doe@pau.edu', '$hash'),
        ('Mr. Librarian', 'Library', 'Main Library', 'library@pau.edu', '$hash'),
        ('Alice Student', 'Student', 'Computer Science', 'alice.student@pau.edu', '$hash')
    ";
    
    // Use IGNORE in case they exist somehow
    $db->exec(str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql));
    echo "Missing users inserted.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
