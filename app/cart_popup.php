<?php
// cart_popup.php
$cart = $_SESSION['cart'] ?? [];
$products = [];
$total = 0;

if ($cart) {
    $ids = array_keys($cart);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $in . ')');
    $stmt->execute($ids);
    while ($row = $stmt->fetch()) {
        $row['qty'] = $cart[$row['id']];
        $row['sum'] = $row['qty'] * $row['price'];
        $products[] = $row;
        $total += $row['sum'];
    }
}
?>

<?php if ($products): ?>
    <h2 style="margin-top:0;">Ваш кошик</h2>
    <table style="width:100%;background:#fff;border-radius:8px;padding:20px;margin-bottom:20px;">
        <tr style="background:#eaeaea;">
            <th>Товар</th>
            <th>Ціна</th>
            <th>Кількість</th>
            <th>Сума</th>
        </tr>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?=htmlspecialchars($p['name'])?></td>
                <td><?=number_format($p['price'],2)?> грн</td>
                <td><?=$p['qty']?></td>
                <td><?=number_format($p['sum'],2)?> грн</td>
            </tr>
        <?php endforeach; ?>
        <tr style="font-weight:bold;">
            <td colspan="3" style="text-align:right;">Всього:</td>
            <td><?=number_format($total,2)?> грн</td>
        </tr>
    </table>
    <a href="checkout.php" style="display:inline-block;background:#1976d2;color:#fff;padding:10px 32px;border:none;border-radius:6px;font-size:1.1em;font-weight:bold;text-decoration:none;text-align:center;">Перейти на чекаут</a>
<?php else: ?>
    <p>Кошик порожній.</p>
<?php endif; ?>