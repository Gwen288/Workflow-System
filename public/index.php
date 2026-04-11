<?php
require_once __DIR__ . '/../config/config.php';

// Simple autoloader
spl_autoload_register(function ($class) {
    // Replace namespaces with directory paths
    $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    
    // Convert 'App/' to 'app/'
    if (strpos($classFile, 'App' . DIRECTORY_SEPARATOR) === 0) {
        $classFile = 'app' . DIRECTORY_SEPARATOR . substr($classFile, 4);
    }
    
    $file = __DIR__ . '/../' . $classFile . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\RequestController;
use App\Controllers\AuditController;
use App\Controllers\SettingsController;

$router = new Router();

// Routes
// Auth
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);

// Requests
$router->get('/requests', [RequestController::class, 'index']);
$router->get('/approvals', [RequestController::class, 'approvals']);
$router->get('/my-requests', [RequestController::class, 'myRequests']);
$router->get('/requests/create', [RequestController::class, 'create']);
$router->post('/requests/store', [RequestController::class, 'store']);
$router->get('/requests/{id}', [RequestController::class, 'show']);
$router->post('/requests/{id}/approve', [RequestController::class, 'approve']);
$router->post('/requests/{id}/reject', [RequestController::class, 'reject']);
$router->post('/requests/{id}/escalate', [RequestController::class, 'escalate']);

// Analytics / AI / Audit / Settings
$router->get('/analytics', [DashboardController::class, 'analytics']);
$router->get('/audit', [AuditController::class, 'index']);
$router->get('/settings', [SettingsController::class, 'index']);
$router->post('/settings/profile', [SettingsController::class, 'updateProfile']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
