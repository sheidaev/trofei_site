<?php
session_start();
include 'db.php';

// Очистити корзину
if (isset($_POST['clear'])) {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$products = [];
$total = 0;

if ($cart) {
    $ids = array_keys($cart);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $in . ')');
    $stmt->execute($ids);
    while ($row = $stmt->fetch()) {
        $row['qty'] = $cart[$row['id']];
        $row['sum'] = $row['qty'] * $row['price'];
        $products[] = $row;
        $total += $row['sum'];
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Кошик</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main-container">
    <div class="header">
        <div class="logo"><a href="index.php" style="color:inherit;text-decoration:none;">&#11044; trofei.ua</a></div>
        <div class="header-right">
            <form method="get" action="search.php" style="display:inline;"><input class="search" type="text" name="q" placeholder="Пошук..." value="<?=isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''?>"></form>
            <div class="cart"><a href="cart.php" style="color:inherit;text-decoration:none;">&#128722; Кошик<?php
                $cartCount = 0;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $c) $cartCount += $c;
                }
                if ($cartCount > 0) {
                    echo '<span style="background:#1976d2;color:#fff;border-radius:50%;padding:2px 8px;font-size:0.9em;margin-left:6px;">' . $cartCount . '</span>';
                }
            ?></a></div>
        </div>
    </div>
    <h1>Ваш кошик</h1>
    <?php if ($products): ?>
        <table style="width:100%;background:#fff;border-radius:8px;padding:20px;margin-bottom:20px;">
            <tr style="background:#eaeaea;">
                <th>Товар</th>
                <th>Ціна</th>
                <th>Кількість</th>
                <th>Сума</th>
            </tr>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?=htmlspecialchars($p['name'])?></td>
                    <td><?=number_format($p['price'],2)?> грн</td>
                    <td><?=$p['qty']?></td>
                    <td><?=number_format($p['sum'],2)?> грн</td>
                </tr>
            <?php endforeach; ?>
            <tr style="font-weight:bold;">
                <td colspan="3" style="text-align:right;">Всього:</td>
                <td><?=number_format($total,2)?> грн</td>
            </tr>
        </table>
        <form method="post">
            <button type="submit" name="clear" style="background:#d32f2f;color:#fff;padding:10px 24px;border:none;border-radius:6px;">Очистити кошик</button>
        </form>
        <form action="checkout.php" method="get" style="margin-top:20px;text-align:right;"><button type="submit" style="background:#1976d2;color:#fff;padding:10px 32px;border:none;border-radius:6px;font-size:1.1em;font-weight:bold;">Оформити замовлення</button></form>
    <?php else: ?>
        <p>Кошик порожній.</p>
    <?php endif; ?>
    <div class="footer" style="margin-top:40px;">
        <div class="footer-left">
            <span><a href="index.php" style="color:inherit;text-decoration:none;">trofei.ua</a></span>
            <span class="footer-payments">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard">
            </span>
        </div>
        <div class="footer-right">
            <span>Приєднуйтесь до нашої розсилки.</span>
            <form id="subscribe-form" style="display:flex;gap:10px;position:relative;"><input type="email" name="email" placeholder="Введіть імейл" required><button type="submit">Підписатись</button><span id="subscribe-popup" style="display:none;position:absolute;left:0;top:110%;background:#eaeaea;color:#1976d2;padding:6px 18px;border-radius:6px;font-size:1em;box-shadow:0 2px 8px #0001;white-space:nowrap;z-index:10;"></span></form>
        </div>
    </div>
</div>
<script>
document.getElementById('subscribe-form').onsubmit = async function(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    const res = await fetch('subscribe.php', {method:'POST', body:data});
    const json = await res.json();
    let popup = document.getElementById('subscribe-popup');
    if(json.success) {
        popup.textContent = 'Дякуємо за підписку!';
        form.reset();
    } else {
        popup.textContent = json.msg || 'Помилка підписки';
    }
    popup.style.display = 'inline-block';
    setTimeout(()=>{ popup.style.display = 'none'; }, 2500);
};
</script>
</body>
</html> 