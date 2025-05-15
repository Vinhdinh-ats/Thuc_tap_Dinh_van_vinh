<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<?php
require '../db.php';
$product_name = $_POST['product_name'];
$unit_price = $_POST['unit_price'];
$stock_quantity = $_POST['stock_quantity'];
$stmt = $pdo->prepare("INSERT INTO products (product_name, unit_price, stock_quantity, created_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$product_name, $unit_price, $stock_quantity]);
header("Location: list.php");
exit;
?>