<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Каталог</title>
</head>
<body>
    <h1>Наші трофеї</h1>
    <div>
        <?php
        $stmt = $pdo->query("SELECT * FROM products");
        while ($row = $stmt->fetch()) {
            echo "<div>
                <a href='product.php?id={$row['id']}'>
                    <img src='{$row['image']}' width='100'><br>
                    {$row['name']}
                </a>
                <div>Ціна: {$row['price']} грн</div>
            </div>";
        }
        ?>
    </div>
</body>
</html>