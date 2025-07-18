<?php
session_start();
include 'db.php';
$cart = $_SESSION['cart'] ?? [];
if (!$cart) { header('Location: cart.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cart_data = json_encode($cart);
    if ($name && $phone && $email) {
        $stmt = $pdo->prepare('INSERT INTO orders (name, phone, email, cart, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$name, $phone, $email, $cart_data]);
        unset($_SESSION['cart']);
        header('Location: success.php');
        exit;
    } else {
        $error = 'Заповніть всі поля';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оформлення замовлення</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main-container">
    <div class="header">
        <div class="logo"><a href="index.php" style="color:inherit;text-decoration:none;">🪖 trofei.ua</a></div>
    </div>
    <h1>Оформлення замовлення</h1>
    <?php if (!empty($error)): ?><div style="color:#d32f2f;"><?=$error?></div><?php endif; ?>
    <form method="post" style="max-width:400px;margin:30px auto 0 auto;display:flex;flex-direction:column;gap:18px;">
        <input type="text" name="name" placeholder="Ім'я" required>
        <input type="tel" name="phone" placeholder="Номер телефону +380..." pattern="\+380[0-9]{9}" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit" style="background:#1976d2;color:#fff;padding:12px 0;border:none;border-radius:6px;font-size:1.1em;font-weight:bold;">Оформити</button>
    </form>
</div>
</body>
</html> 