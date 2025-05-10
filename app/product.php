<?php
include 'db.php';
$id = (int)($_GET['id'] ?? 0);
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
        <div class="logo">&#11044; trofei.ua</div>
        <div class="header-right">
            <input class="search" type="text" placeholder="Пошук...">
            <div class="cart">&#128722; Кошик</div>
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
                <button type="submit">Купити</button>
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
                        <img src="<?=htmlspecialchars(explode(',', $sim['image'])[0])?>" alt="<?=htmlspecialchars($sim['name'])?>">
                        <div class="trophy-name"><?=htmlspecialchars($sim['name'])?></div>
                        <div class="trophy-price">Ціна: <?=number_format($sim['price'],2)?> грн</div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="footer" style="margin-top:40px;">
        <div class="footer-left">
            <span>trofei.ua</span>
            <span class="footer-payments">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard">
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