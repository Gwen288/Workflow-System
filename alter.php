<?php
$host = 'localhost';
$db   = 'workflow_system';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    try {
        $pdo->exec("ALTER TABLE Request ADD COLUMN metadata TEXT DEFAULT NULL");
        echo "Column metadata added successfully.\n";
    } catch (\Exception $e) {
        echo "Metadata column might already exist: " . $e->getMessage() . "\n";
    }

    try {
        $pdo->exec("ALTER TABLE Request ADD COLUMN attachment_path VARCHAR(255) DEFAULT NULL");
        echo "Column attachment_path added successfully.\n";
    } catch (\Exception $e) {
        echo "Attachment path column might already exist: " . $e->getMessage() . "\n";
    }
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
