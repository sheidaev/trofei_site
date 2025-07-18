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
            <div class="logo"><a href="index.php" style="color:inherit;text-decoration:none;">🪖 trofei.ua</a></div>
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
            <div class="trophies-list" id="products-list"></div>
        </div>

        <div class="footer">
            <div class="footer-left">
                <span><a href="index.php" style="color:inherit;text-decoration:none;">🪖 trofei.ua</a></span>
                <span class="footer-payments">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard">
                </span>
            </div>
            <div class="footer-right">
                <span>Приєднуйтесь до нашої розсилки.</span>
                <form id="subscribe-form" style="display:flex;gap:10px;position:relative;">
                    <input type="email" id="subscribe-email" name="email" placeholder="Введіть імейл" required>
                    <button type="submit" id="subscribe-button">Підписатись</button>
                    <span id="subscribe-popup" style="display:none;position:absolute;left:0;top:110%;background:#eaeaea;color:#1976d2;padding:6px 18px;border-radius:6px;font-size:1em;box-shadow:0 2px 8px #0001;white-space:nowrap;z-index:10;"></span>
                </form>
            </div>
        </div>
    </div>
    <div id="center-popup" style="display:none;position:fixed;left:0;top:0;width:100vw;height:100vh;z-index:1000;background:rgba(0,0,0,0.25);align-items:center;justify-content:center;">
  <div style="background:#fff;padding:32px 40px;border-radius:12px;box-shadow:0 4px 32px #0003;font-size:1.2em;color:#1976d2;text-align:center;max-width:90vw;max-height:80vh;overflow:auto;">
    <span id="center-popup-text"></span>
  </div>
</div>
    <script>
    document.getElementById('subscribe-form').onsubmit = async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        const res = await fetch('subscribe.php', {method:'POST', body:data});
        const json = await res.json();
        if(json.success) {
            document.getElementById('center-popup-text').textContent = 'Дякуємо! Ви будете першим, хто отримає інформацію про новий трофей!';
            document.getElementById('center-popup').style.display = 'flex';
            form.reset();
            setTimeout(()=>{ document.getElementById('center-popup').style.display = 'none'; }, 2500);
        } else {
            let popup = document.getElementById('subscribe-popup');
            popup.textContent = json.msg || 'Помилка підписки';
            popup.style.display = 'inline-block';
            setTimeout(()=>{ popup.style.display = 'none'; }, 2500);
        }
    };
    async function loadProducts() {
        const res = await fetch('api/products.php?limit=6');
        const products = await res.json();
        const list = document.getElementById('products-list');
        list.innerHTML = '';
        products.forEach(row => {
            list.innerHTML += `
                <div class='trophy-card'>
                    <a href='product.php?id=${row.id}'>
                        <img src='images/${encodeURIComponent(row.image)}' alt='${escapeHtml(row.name)}'>
                        <div class='trophy-name'>${escapeHtml(row.name)}</div>
                    </a>
                    <div class='trophy-price'>Ціна: ${row.price} грн</div>
                </div>
            `;
        });
    }
    function escapeHtml(str) {
        return str.replace(/[&<>'"]/g, t => ({
            '&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'
        }[t]));
    }
    loadProducts();
    </script>
</body>
</html>