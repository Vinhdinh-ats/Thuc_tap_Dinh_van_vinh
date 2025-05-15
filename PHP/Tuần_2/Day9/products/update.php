<?php
require '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
     die('Thiếu ID sản phẩm.');
}

// Lấy thông tin sản phẩm hiện tại
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
     die('Không tìm thấy sản phẩm.');
}

// Xử lý cập nhật khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $name = $_POST['product_name'];
     $price = $_POST['unit_price'];
     $stock = $_POST['stock_quantity'];

     $stmt = $pdo->prepare("UPDATE products SET product_name = ?, unit_price = ?, stock_quantity = ? WHERE id = ?");
     $stmt->execute([$name, $price, $stock, $id]);

     header("Location: list.php");
     exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>Cập nhật sản phẩm</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
     <div class="container mt-4">
          <h2>Cập nhật sản phẩm</h2>
          <form method="POST">
               <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" class="form-control" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
               </div>
               <div class="mb-3">
                    <label class="form-label">Đơn giá (VNĐ)</label>
                    <input type="number" class="form-control" name="unit_price" value="<?= $product['unit_price'] ?>" step="1000" required>
               </div>
               <div class="mb-3">
                    <label class="form-label">Số lượng tồn kho</label>
                    <input type="number" class="form-control" name="stock_quantity" value="<?= $product['stock_quantity'] ?>" required>
               </div>
               <button type="submit" class="btn btn-primary">Cập nhật</button>
               <a href="list.php" class="btn btn-secondary">Quay lại</a>
          </form>
     </div>
</body>

</html>