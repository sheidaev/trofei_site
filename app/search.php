<?php
session_start();
include 'db.php';
$q = trim($_GET['q'] ?? '');
$results = [];
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name ILIKE ? OR description ILIKE ? LIMIT 30");
    $like = "%$q%";
    $stmt->execute([$like, $like]);
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Пошук: <?=htmlspecialchars($q)?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main-container">
    <div class="header">
        <div class="logo"><a href="index.php" style="color:inherit;text-decoration:none;">&#11044; trofei.ua</a></div>
        <div class="header-right">
            <form method="get" action="search.php" style="display:inline;"><input class="search" type="text" name="q" placeholder="Пошук..." value="<?=htmlspecialchars($q)?>"></form>
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
    <div class="trophies-section">
        <div class="trophies-title">Результати пошуку: "<?=htmlspecialchars($q)?>"</div>
        <div class="trophies-list">
            <?php
            if ($q === '') {
                echo '<div>Введіть запит для пошуку.</div>';
            } elseif ($results) {
                foreach ($results as $row) {
                    echo "<div class='trophy-card'>
                        <a href='product.php?id={$row['id']}'>
                            <img src='images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>
                            <div class='trophy-name'>" . htmlspecialchars($row['name']) . "</div>
                        </a>
                        <div class='trophy-price'>Ціна: {$row['price']} грн</div>
                    </div>";
                }
            } else {
                echo '<div>Нічого не знайдено.</div>';
            }
            ?>
        </div>
    </div>
    <div class="footer">
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