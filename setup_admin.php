<?php
/**
 * PAU Workflow - Admin Bootstrap Script
 * This script creates the initial System Administrator account.
 */
require_once __DIR__ . '/config/config.php';

// Simple autoloader
spl_autoload_register(function ($class) {
    $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    if (strpos($classFile, 'App' . DIRECTORY_SEPARATOR) === 0) {
        $classFile = 'app' . DIRECTORY_SEPARATOR . substr($classFile, 4);
    }
    $file = __DIR__ . '/' . $classFile . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Models\User;

echo "--- PAU Workflow Admin Bootstrap ---\n";

$userModel = new User();
$adminEmail = 'admin@pau.edu';

// Check if admin already exists
if ($userModel->findByEmail($adminEmail)) {
    echo "Aborting: Admin account ($adminEmail) already exists.\n";
    exit;
}

// Create Admin
$password = 'Admin@123';
$hashed = password_hash($password, PASSWORD_DEFAULT);

$userId = $userModel->create([
    'name' => 'System Administrator',
    'email' => $adminEmail,
    'password' => $hashed,
    'role' => 'Admin',
    'department' => 'IT Operations'
]);

if ($userId) {
    echo "Success! Admin account created.\n";
    echo "Email: $adminEmail\n";
    echo "Password: $password\n";
    echo "------------------------------------\n";
} else {
    echo "Error: Failed to create admin account.\n";
}
