<?php
session_start();

// Custom Exception Class
class CartException extends Exception {}

// Hàm ghi log lỗi
function logError($message)
{
     $log_dir = 'logs/';
     $log_file = $log_dir . 'log.txt';
     if (!file_exists($log_dir)) {
          mkdir($log_dir, 0777, true);
     }
     $log_entry = "[" . date('Y-m-d H:i:s') . "] ERROR: $message" . PHP_EOL;
     file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Hàm thêm sản phẩm vào giỏ hàng
function addToCart($title, $quantity, $price)
{
     try {
          if (!isset($_SESSION['cart'])) {
               $_SESSION['cart'] = [];
          }

          // Cộng dồn số lượng nếu sách đã có trong giỏ
          $found = false;
          foreach ($_SESSION['cart'] as &$item) {
               if ($item['title'] === $title) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
               }
          }
          if (!$found) {
               $_SESSION['cart'][] = [
                    'title' => $title,
                    'quantity' => $quantity,
                    'price' => $price
               ];
          }
     } catch (Exception $e) {
          logError("Failed to add to cart: " . $e->getMessage());
          throw new CartException("Unable to add item to cart.");
     }
}

// Hàm lưu giỏ hàng vào file JSON
function saveCartToJson($customer_info)
{
     try {
          $cart_data = [
               'customer_email' => $customer_info['email'],
               'products' => $_SESSION['cart'] ?? [],
               'total_amount' => calculateTotal(),
               'created_at' => date('Y-m-d H:i:s')
          ];

          $json_data = json_encode($cart_data, JSON_PRETTY_PRINT);
          if ($json_data === false) {
               throw new CartException("Failed to encode cart data to JSON.");
          }

          if (!file_put_contents('cart_data.json', $json_data)) {
               throw new CartException("Failed to write cart data to file.");
          }
     } catch (Exception $e) {
          logError("Failed to save cart to JSON: " . $e->getMessage());
          throw $e;
     }
}

// Hàm tính tổng tiền
function calculateTotal()
{
     $total = 0;
     if (isset($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $item) {
               $total += $item['price'] * $item['quantity'];
          }
     }
     return $total;
}

// Hàm xóa giỏ hàng
function clearCart()
{
     try {
          unset($_SESSION['cart']);
          if (file_exists('cart_data.json')) {
               unlink('cart_data.json');
          }
     } catch (Exception $e) {
          logError("Failed to clear cart: " . $e->getMessage());
          throw new CartException("Unable to clear cart.");
     }
}
