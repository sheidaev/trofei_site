<?php
header('Content-Type: application/json');
include 'db.php';
$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success'=>false, 'msg'=>'Некоректний email']);
    exit;
}
try {
    $stmt = $pdo->prepare('INSERT INTO subscribers (email) VALUES (?) ON CONFLICT (email) DO NOTHING');
    $stmt->execute([$email]);
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    echo json_encode(['success'=>false, 'msg'=>'Помилка підписки']);
} 