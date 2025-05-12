<?php
header('Content-Type: application/json');
include '../db.php';
$where = [];
$params = [];
if (!empty($_GET['category'])) {
    $cat = is_array($_GET['category']) ? $_GET['category'] : [$_GET['category']];
    $in = implode(',', array_fill(0, count($cat), '?'));
    $where[] = 'category IN (' . $in . ')';
    $params = array_merge($params, $cat);
}
if (!empty($_GET['source'])) {
    $src = is_array($_GET['source']) ? $_GET['source'] : [$_GET['source']];
    $in = implode(',', array_fill(0, count($src), '?'));
    $where[] = 'source IN (' . $in . ')';
    $params = array_merge($params, $src);
}
if (!empty($_GET['color'])) {
    $col = is_array($_GET['color']) ? $_GET['color'] : [$_GET['color']];
    $in = implode(',', array_fill(0, count($col), '?'));
    $where[] = 'color IN (' . $in . ')';
    $params = array_merge($params, $col);
}
$sql = 'SELECT * FROM products';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
if (!empty($_GET['sort'])) {
    if ($_GET['sort'] == 'price_asc') $sql .= ' ORDER BY price ASC';
    if ($_GET['sort'] == 'price_desc') $sql .= ' ORDER BY price DESC';
}
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
$sql .= ' LIMIT ' . $limit;
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products); 