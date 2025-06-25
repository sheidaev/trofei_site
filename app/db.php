<?php
$host = $_ENV['DB_HOST'] ?? 'db';
$db   = $_ENV['DB_NAME'] ?? 'trofei';
$user = $_ENV['DB_USER'] ?? 'trofei_user';
$pass = $_ENV['DB_PASSWORD'] ?? 'trofei_pass';
$dsn = "pgsql:host=$host;port=5432;dbname=$db;";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "DB error: " . $e->getMessage();
    exit;
}
?>