<?php include 'db.php'; ?>
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
            <div class="logo">&#11044; trofei.ua</div>
            <div class="header-right">
                <input class="search" type="text" placeholder="Пошук...">
                <div class="cart">&#128722; Кошик</div>
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
                            <img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>
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
                <span>trofei.ua</span>
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