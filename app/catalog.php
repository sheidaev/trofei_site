<?php
session_start(); 
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Каталог трофеїв</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main-container-catalog">
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
    <div class="catalog-content">
        <aside class="catalog-filters">
            <form method="get">
                 <div class="filter-group">
                    <div class="filter-title">Сортування</div>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="price_asc" <?=isset($_GET['sort']) && $_GET['sort']==='price_asc' ? 'selected' : ''?>> Від дешевих до дорогих </option>
                        <option value="price_desc" <?=isset($_GET['sort']) && $_GET['sort']==='price_desc' ? 'selected' : ''?>> Від дорогих до дешевих </option>
                    </select>
                </div>
                <div class="filter-group">
                    <div class="filter-title">Категорія</div>
                    <label><input type="checkbox" name="category[]" value="Шеврони"> Шеврони</label><br>
                    <label><input type="checkbox" name="category[]" value="Зброя"> Зброя</label><br>
                    <label><input type="checkbox" name="category[]" value="Одяг"> Одяг</label><br>
                    <label><input type="checkbox" name="category[]" value="Інструменти"> Інструменти</label><br>
                    <label><input type="checkbox" name="category[]" value="Їжа"> Їжа</label>
                </div>
                <div class="filter-group">
                    <div class="filter-title">З кого отримали</div>
                    <label><input type="checkbox" name="source[]" value="Полонений"> Полонений</label><br>
                    <label><input type="checkbox" name="source[]" value="Хороший"> Хороший</label><br>
                    <label><input type="checkbox" name="source[]" value="Знайшли на позиції"> Знайшли на позиції</label>
                </div>
                <div class="filter-group">
                    <div class="filter-title">Колір</div>
                    <label><input type="checkbox" name="color[]" value="Білий"> <span style="color:#fff;background:#000;border:1px solid #000;">Білий</span></label><br>
                    <label><input type="checkbox" name="color[]" value="Чорний"> <span style="color:#000;">Чорний</span></label><br>
                    <label><input type="checkbox" name="color[]" value="Синій"> <span style="color:blue;">Синій</span></label><br>
                    <label><input type="checkbox" name="color[]" value="Оранжевий"> <span style="color:orange;">Оранжевий</span></label><br>
                    <label><input type="checkbox" name="color[]" value="Червоний"> <span style="color:red;">Червоний</span></label><br>
                    <label><input type="checkbox" name="color[]" value="Зелений"> <span style="color:green;">Зелений</span></label>
                </div>
                <button type="submit">Застосувати</button>
                <a href="catalog.php">Очистити</a>
            </form>
        </aside>
        <section class="catalog-list">
            <div class="trophies-list" id="products-list"></div>
        </section>
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
            <form id="subscribe-form" style="display:flex;gap:10px;position:relative;"><input type="email" name="email" placeholder="Введіть імейл" required><button type="submit">Підписатись</button><span id="subscribe-popup" style="display:none;position:absolute;left:0;top:110%;background:#eaeaea;color:#1976d2;padding:6px 18px;border-radius:6px;font-size:1em;box-shadow:0 2px 8px #0001;white-space:nowrap;z-index:10;"></span></form>
        </div>
    </div>
</div>
<script>
function getFiltersFromUrl() {
    const params = new URLSearchParams(window.location.search);
    let url = 'api/products.php?';
    for (const [key, value] of params.entries()) {
        if (Array.isArray(value)) {
            value.forEach(v => url += `${key}[]=${encodeURIComponent(v)}&`);
        } else {
            url += `${key}=${encodeURIComponent(value)}&`;
        }
    }
    return url;
}
async function loadProducts() {
    const url = getFiltersFromUrl();
    const res = await fetch(url);
    const products = await res.json();
    const list = document.getElementById('products-list');
    list.innerHTML = '';
    if (products.length === 0) {
        list.innerHTML = '<div style="padding:30px;">Нічого не знайдено.</div>';
        return;
    }
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
window.addEventListener('DOMContentLoaded', loadProducts);
window.addEventListener('popstate', loadProducts);
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