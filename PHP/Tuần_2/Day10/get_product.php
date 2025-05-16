<?php
require 'db.php';

if (isset($_GET['id'])) {
     $id = intval($_GET['id']);

     $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
     $stmt->execute([$id]);

     if ($product = $stmt->fetch()) {
?>
          <ul class="nav nav-tabs mb-3" id="productTabs">
               <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="showTab(event, 'detail-tab')">Chi tiết</a>
               </li>
               <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadReviews(<?= $id ?>)">Đánh giá</a>
               </li>
          </ul>

          <div id="detail-tab">
               <h4><?= htmlspecialchars($product['name']) ?></h4>
               <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
               <ul class="list-group">
                    <li class="list-group-item"><strong>Giá:</strong> <?= number_format($product['price'], 0, ',', '.') ?> VNĐ</li>
                    <li class="list-group-item"><strong>Tồn kho:</strong> <?= $product['stock'] ?></li>
               </ul>
          </div>

          <div id="review-tab" style="display: none;">
               <p class="text-muted">Đang tải đánh giá...</p>
          </div>
<?php
     } else {
          echo '<div class="alert alert-warning">Không tìm thấy sản phẩm.</div>';
     }
} else {
     echo '<div class="alert alert-danger">Thiếu ID sản phẩm.</div>';
}
?>