<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>Danh sách sản phẩm</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
     <div class="container mt-4">

          <?php require '../db.php'; ?>
          <link rel="stylesheet" href="../assets/bootstrap.min.css">
          <div class="container mt-5">
               <h2>Danh sách sản phẩm</h2>
               <table class="table table-bordered">
                    <thead>
                         <tr>
                              <th>ID</th>
                              <th>Tên</th>
                              <th>Giá</th>
                              <th>Tồn kho</th>
                              <th>Ngày tạo</th>
                              <th>Hành động</th>
                         </tr>
                    </thead>
                    <tbody>

                         <?php $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
                         foreach ($stmt as $row): ?>
                              <tr>
                                   <td><?= $row['id'] ?></td>
                                   <td><?= $row['product_name'] ?></td>
                                   <td><?= number_format($row['unit_price']) ?> VNĐ</td>
                                   <td><?= $row['stock_quantity'] ?></td>
                                   <td><?= $row['created_at'] ?></td>
                                   <td><a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Xóa</a> <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a></td>
                              </tr>

                         <?php endforeach ?>
                    </tbody>
               </table>
          </div>
     </div>
</body>

</html>