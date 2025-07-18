<?php
session_start();
include 'db.php';
$id = (int)($_GET['id'] ?? 0);

// Додавання у корзину
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty'])) {
    $qty = max(1, min(99, (int)$_POST['qty']));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
    // Після додавання можна зробити редірект, щоб уникнути повторного додавання при оновленні сторінки
    header('Location: product.php?id=' . $id . '&added=1');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { echo "Товар не знайдено"; exit; }

// Галерея (приклад: через кому в полі image)
$images = array_map('trim', explode(',', $product['image']));
$images = array_map(function($img) { return 'images/' . $img; }, $images);
$mainImage = $images[0] ?? '';

// Схожі товари (по категорії, крім поточного)
$similar = [];
if (!empty($product['category'])) {
    $stmt2 = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 5");
    $stmt2->execute([$product['category'], $id]);
    $similar = $stmt2->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="style.css">
    <script>
    // Галерея: зміна головного фото
    function setMainImage(src) {
        document.getElementById('mainProductImg').src = src;
        let thumbs = document.querySelectorAll('.product-thumb');
        thumbs.forEach(t => t.classList.remove('selected'));
        let sel = Array.from(thumbs).find(t => t.src === src);
        if (sel) sel.classList.add('selected');
    }
    </script>
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
                echo '<span id="cart-counter" style="background:#1976d2;color:#fff;border-radius:50%;padding:2px 8px;font-size:0.9em;margin-left:6px;">' . $cartCount . '</span>';
            }
            ?></a></div>
        </div>
    </div>

    <div class="breadcrumbs">
        <a href="index.php">Головна</a> &gt; 
        <?php if (!empty($product['category'])): ?>
            <a href="catalog.php?category[]=<?=urlencode($product['category'])?>"><?=htmlspecialchars($product['category'])?></a> &gt; 
        <?php endif; ?>
        <?=htmlspecialchars($product['name'])?>
    </div>

    <div class="product-container">
        <div class="product-gallery">
            <img id="mainProductImg" class="product-main-img" src="<?=htmlspecialchars($mainImage)?>" alt="<?=htmlspecialchars($product['name'])?>">
            <div class="product-thumbs">
                <?php foreach ($images as $i => $img): ?>
                    <img class="product-thumb<?= $i===0 ? ' selected' : '' ?>" src="<?=htmlspecialchars($img)?>" onclick="setMainImage(this.src)">
                <?php endforeach; ?>
            </div>
        </div>
        <div class="product-info">
            <div class="product-title"><?=htmlspecialchars($product['name'])?></div>
            <div class="product-price"><?=number_format($product['price'], 2)?> грн</div>
            <div class="product-desc"><?=htmlspecialchars($product['description'])?></div>
            <form class="product-buy" method="post" action="#">
                <label for="qty">Кількість</label>
                <input type="number" id="qty" name="qty" value="1" min="1" max="99">
                <button type="submit" id="buy-button">Купити</button>
            </form>
            <div class="product-donate">Все піде на донати</div>
        </div>
    </div>

    <?php if ($similar): ?>
        <div class="similar-title">Ще <?=htmlspecialchars($product['category'])?></div>
        <div class="similar-list">
            <?php foreach ($similar as $sim): ?>
                <div class="similar-card">
                    <a href="product.php?id=<?=$sim['id']?>">
                        <img src="images/<?=htmlspecialchars(trim(explode(',', $sim['image'])[0]))?>" alt="<?=htmlspecialchars($sim['name'])?>">
                        <div class="trophy-name"><?=htmlspecialchars($sim['name'])?></div>
                        <div class="trophy-price">Ціна: <?=number_format($sim['price'],2)?> грн</div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="footer" style="margin-top:40px;">
        <div class="footer-left">
            <span><a href="index.php" style="color:inherit;text-decoration:none;">🪖 trofei.ua</a></span>
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
</script>
</body>
</html>