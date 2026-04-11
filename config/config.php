<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'workflow_system');
define('DB_USER', 'root');
define('DB_PASS', '');

// Load helpers
require_once __DIR__ . '/helpers.php';
