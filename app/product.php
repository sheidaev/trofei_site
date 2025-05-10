<?php
include 'db.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { echo "Товар не знайдено"; exit; }
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($product['name']) ?></h1>
    <img src="<?= htmlspecialchars($product['image']) ?>" width="200">
    <div><?= htmlspecialchars($product['description']) ?></div>
    <div>Ціна: <?= $product['price'] ?> грн</div>
    <button>Купити</button>
</body>
</html>