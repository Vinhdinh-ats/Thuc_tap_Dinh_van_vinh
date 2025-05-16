<?php
session_start();
header('Content-Type: application/json');

// Đọc JSON từ fetch body
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_id'])) {
     $id = intval($data['product_id']);

     // Khởi tạo giỏ hàng nếu chưa có
     if (!isset($_SESSION['cart'])) {
          $_SESSION['cart'] = [];
     }

     // Tăng số lượng sản phẩm trong giỏ
     if (!isset($_SESSION['cart'][$id])) {
          $_SESSION['cart'][$id] = 1;
     } else {
          $_SESSION['cart'][$id]++;
     }

     // Tổng số lượng sản phẩm
     $totalCount = array_sum($_SESSION['cart']);

     echo json_encode([
          'success' => true,
          'cartCount' => $totalCount
     ]);
} else {
     echo json_encode(['success' => false]);
}
