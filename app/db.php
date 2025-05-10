<?php
$host = 'db';
$db   = 'trofei';
$user = 'trofei_user';
$pass = 'trofei_pass';
$dsn = "pgsql:host=$host;port=5432;dbname=$db;";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "DB error: " . $e->getMessage();
    exit;
}
?>