<?php
// search.php
header('Content-Type: text/html; charset=UTF-8');

// Kết nối DB
$pdo = new PDO("mysql:host=localhost;dbname=shop_db;charset=utf8", "root", "");

// Lấy từ khóa
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($keyword === '') {
     echo '<div class="col">Không có kết quả.</div>';
     exit;
}

// Truy vấn sản phẩm có tên giống từ khóa
$stmt = $pdo->prepare("SELECT id, name, description, price FROM products WHERE name LIKE ?");
$stmt->execute(["%$keyword%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$results) {
     echo '<div class="col">Không tìm thấy sản phẩm nào.</div>';
     exit;
}

// Trả về HTML dạng Bootstrap cards
foreach ($results as $item) {
     echo '
    <div class="col">
      <div class="card h-100">
        
        <div class="card-body">
          <h5 class="card-title">' . htmlspecialchars($item['name']) . '</h5>
          <p class="card-text text-danger fw-bold">' . number_format($item['price'], 0, ',', '.') . ' ₫</p>
        </div>
      </div>
    </div>';
}
