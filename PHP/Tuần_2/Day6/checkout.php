<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Book Store - Checkout</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <?php include 'includes/header.php'; ?>
     <div class="container mt-5">
          <h1 class="mb-4">Checkout</h1>
          <?php
          require_once 'includes/cart.php';

          if (isset($_POST['confirm_order'])) {
               try {
                    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                         throw new CartException("Your cart is empty.");
                    }
                    saveCartToJson($_SESSION['customer']);
                    echo '<div class="alert alert-success">Order confirmed! Details saved.</div>';
               } catch (CartException $e) {
                    echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
               } catch (Exception $e) {
                    logError($e->getMessage());
                    echo '<div class="alert alert-danger">An unexpected error occurred.</div>';
               }
          }

          if (isset($_POST['clear_cart'])) {
               try {
                    clearCart();
                    echo '<div class="alert alert-success">Cart cleared successfully!</div>';
               } catch (CartException $e) {
                    echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
               }
          }

          // Hiển thị giỏ hàng
          if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
               echo '<h3>Your Cart</h3>';
               echo '<table class="table table-striped">';
               echo '<thead><tr><th>Title</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead>';
               echo '<tbody>';
               $total = 0;
               foreach ($_SESSION['cart'] as $item) {
                    $item_total = $item['price'] * $item['quantity'];
                    $total += $item_total;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($item['title']) . "</td>";
                    echo "<td>" . number_format($item['price']) . " VND</td>";
                    echo "<td>" . $item['quantity'] . "</td>";
                    echo "<td>" . number_format($item_total) . " VND</td>";
                    echo "</tr>";
               }
               echo "<tr><td colspan='3'><strong>Total</strong></td><td><strong>" . number_format($total) . " VND</strong></td></tr>";
               echo '</tbody></table>';

               // Hiển thị thông tin khách hàng
               if (isset($_SESSION['customer'])) {
                    echo '<h3>Customer Information</h3>';
                    echo '<p><strong>Name:</strong> ' . htmlspecialchars($_SESSION['customer']['name']) . '</p>';
                    echo '<p><strong>Email:</strong> ' . htmlspecialchars($_SESSION['customer']['email']) . '</p>';
                    echo '<p><strong>Phone:</strong> ' . htmlspecialchars($_SESSION['customer']['phone']) . '</p>';
                    echo '<p><strong>Address:</strong> ' . htmlspecialchars($_SESSION['customer']['address']) . '</p>';
                    echo '<p><strong>Order Time:</strong> ' . date('Y-m-d H:i:s') . '</p>';
               }

               // Nút xác nhận và xóa giỏ hàng
               echo '<form action="checkout.php" method="post" class="mt-3">';
               echo '<button type="submit" name="confirm_order" class="btn btn-primary me-2">Confirm Order</button>';
               echo '<button type="submit" name="clear_cart" class="btn btn-danger">Clear Cart</button>';
               echo '</form>';
          } else {
               echo '<div class="alert alert-info">Your cart is empty.</div>';
          }
          ?>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>