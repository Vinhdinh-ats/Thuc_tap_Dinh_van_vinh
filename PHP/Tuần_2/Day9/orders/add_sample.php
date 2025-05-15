<link href="../assets/bootstrap.min.css" rel="stylesheet">
<?php require '../db.php';
$orders = [['order' => ['2025-05-01', 'Khách A', 'Giao trong tuần'], 'items' => [[1, 2, 1200000], [2, 1, 800000]]], ['order' => ['2025-05-01', 'Khách B', null], 'items' => [[3, 1, 2000000], [5, 2, 950000]]]];
foreach ($orders as $o) {
     $stmtOrder = $pdo->prepare("INSERT INTO orders (order_date, customer_name, note) VALUES (?, ?, ?)");
     $stmtOrder->execute($o['order']);
     $order_id = $pdo->lastInsertId();
     foreach ($o['items'] as $item) {
          $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_order_time) VALUES (?, ?, ?, ?)");
          $stmtItem->execute([$order_id, $item[0], $item[1], $item[2]]);
     }
}
echo $order_id;
echo "Đã thêm đơn hàng mẫu. <a href='../index.php'>Quay lại</a>"; ?>