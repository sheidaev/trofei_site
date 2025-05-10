<?php include 'db.php'; ?>
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
        <!-- ...шапка як на головній... -->
    </div>
    <div class="catalog-content">
        <aside class="catalog-filters">
            <form method="get">
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
            <div class="catalog-sort">
                <select name="sort" onchange="this.form.submit()">
                    <option value="">Сортування</option>
                    <option value="price_asc">Ціна ↑</option>
                    <option value="price_desc">Ціна ↓</option>
                </select>
            </div>
            <div class="trophies-list">
                <?php
                // Тут логіка фільтрації та сортування
                $where = [];
                $params = [];
                if (!empty($_GET['category'])) {
                    $in = implode(',', array_fill(0, count($_GET['category']), '?'));
                    $where[] = 'category IN (' . $in . ')';
                    $params = array_merge($params, $_GET['category']);
                }
                if (!empty($_GET['source'])) {
                    $in = implode(',', array_fill(0, count($_GET['source']), '?'));
                    $where[] = 'source IN (' . $in . ')';
                    $params = array_merge($params, $_GET['source']);
                }
                if (!empty($_GET['color'])) {
                    $in = implode(',', array_fill(0, count($_GET['color']), '?'));
                    $where[] = 'color IN (' . $in . ')';
                    $params = array_merge($params, $_GET['color']);
                }
                $sql = 'SELECT * FROM products';
                if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
                if (!empty($_GET['sort'])) {
                    if ($_GET['sort'] == 'price_asc') $sql .= ' ORDER BY price ASC';
                    if ($_GET['sort'] == 'price_desc') $sql .= ' ORDER BY price DESC';
                }
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                while ($row = $stmt->fetch()) {
                    echo "<div class='trophy-card'>
                        <a href='product.php?id={$row['id']}'>
                            <img src='images/{$row['image']}' alt='{$row['name']}'>
                            <div class='trophy-name'>{$row['name']}</div>
                        </a>
                        <div class='trophy-price'>Ціна: {$row['price']} грн</div>
                    </div>";
                }
                ?>
            </div>
        </section>
    </div>
    <div class="footer">
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