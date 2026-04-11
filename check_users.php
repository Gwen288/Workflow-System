<?php
require_once __DIR__ . '/app/Core/Database.php';

// Ensure constants are defined for standalone script if needed
if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', 'workflow_system');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

try {
    $db = \App\Core\Database::getInstance();
    $stmt = $db->query("SELECT email, password, role FROM User");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($users);
    
    // Also test password_verify
    $password = 'password123';
    foreach ($users as $user) {
        $isValid = password_verify($password, $user['password']) ? 'YES' : 'NO';
        echo "Email: {$user['email']} | Verifies: $isValid\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
