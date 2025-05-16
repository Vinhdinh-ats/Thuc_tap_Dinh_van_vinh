<?php
require 'db.php';

if (!isset($_GET['product_id'])) {
     echo '<div class="alert alert-danger">Thiếu ID sản phẩm.</div>';
     exit;
}

$id = intval($_GET['product_id']);

$stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ?");
$stmt->execute([$id]);
$reviews = $stmt->fetchAll();

if (!$reviews) {
     echo '<div class="alert alert-info">Chưa có đánh giá nào cho sản phẩm này.</div>';
} else {
     foreach ($reviews as $review) {
          $stars = str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']);
?>
          <div class="border rounded p-3 mb-2">
               <h6 class="mb-1"><?= htmlspecialchars($review['reviewer_name']) ?> –
                    <span class="text-warning"><?= $stars ?></span>
               </h6>
               <p class="mb-0"><?= nl2br(htmlspecialchars($review['content'])) ?></p>
          </div>
<?php
     }
}
?>