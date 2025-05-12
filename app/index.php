<?php
session_start();
include 'db.php'; ?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>trofei.ua</title>
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

        <div class="banner">
            <h1>3 нас трофей — з тебе донат!</h1>
            <p>Наші гіди по трофеях допоможуть ідентифікувати знахідки у всіх регіонах України. Завантажуй будь-який гід безкоштовно просто зараз!</p>
            <a class="banner-btn" href="catalog.php">&#128194; Переглянути трофеї</a>
        </div>

        <div class="trophies-section">
            <div class="trophies-title">Наші трофеї</div>
            <div class="trophies-list">
                <?php
                $stmt = $pdo->query("SELECT * FROM products LIMIT 6");
                while ($row = $stmt->fetch()) {
                    echo "<div class='trophy-card'>
                        <a href='product.php?id={$row['id']}'>
                            <img src='images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>
                            <div class='trophy-name'>" . htmlspecialchars($row['name']) . "</div>
                        </a>
                        <div class='trophy-price'>Ціна: {$row['price']} грн</div>
                    </div>";
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
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Amex-logo.png" alt="Amex">
                </span>
            </div>
            <div class="footer-right">
                <span>Приєднуйтесь до нашої розсилки.</span>
                <input type="email" placeholder="Введіть імейл">
                <button>Підписатись</button>
            </div>
        </div>
    </div>
</body>
</html>