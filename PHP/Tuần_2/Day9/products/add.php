<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>Thêm sản phẩm</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
     <div class="container mt-4">
          <link rel="stylesheet" href="../assets/bootstrap.min.css">
          <div class="container mt-5">
               <h2>Thêm Sản Phẩm</h2>
               <form method="POST" action="insert.php">
                    <div class="mb-3">
                         <label class="form-label">Tên sản phẩm</label>
                         <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                         <label class="form-label">Giá</label>
                         <input type="number" name="unit_price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                         <label class="form-label">Tồn kho</label>
                         <input type="number" name="stock_quantity" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm</button>
               </form>
          </div>
     </div>
</body>

</html>