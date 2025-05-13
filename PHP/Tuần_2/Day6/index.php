<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Book Store - Add to Cart</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <?php include 'includes/header.php'; ?>
     <div class="container mt-5">
          <h1 class="mb-4">Add Books to Cart</h1>
          <form action="index.php" method="post">
               <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_COOKIE['customer_name']) ? htmlspecialchars($_COOKIE['customer_name']) : ''; ?>" required>
               </div>
               <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_COOKIE['customer_email']) ? htmlspecialchars($_COOKIE['customer_email']) : ''; ?>" required>
               </div>
               <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
               </div>
               <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
               </div>
               <div class="mb-3">
                    <label for="book" class="form-label">Select Book</label>
                    <select class="form-select" id="book" name="book" required>
                         <option value="">Choose a book</option>
                         <option value="Clean Code|150000">Clean Code - 150,000 VND</option>
                         <option value="Design Patterns|200000">Design Patterns - 200,000 VND</option>
                         <option value="Refactoring|180000">Refactoring - 180,000 VND</option>
                    </select>
               </div>
               <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
               </div>
               <button type="submit" class="btn btn-primary" name="add_to_cart">Add to Cart</button>
          </form>

          <?php
          require_once 'includes/cart.php';

          if (isset($_POST['add_to_cart'])) {
               try {
                    // Lọc và xác thực đầu vào
                    $name = filter_input(INPUT_POST, 'name');
                    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                    $phone = filter_input(INPUT_POST, 'phone', FILTER_VALIDATE_REGEXP, [
                         'options' => ['regexp' => '/^[0-9]{10,11}$/']
                    ]);
                    $address = filter_input(INPUT_POST, 'address');
                    $book = filter_input(INPUT_POST, 'book');
                    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT, [
                         'options' => ['min_range' => 1]
                    ]);

                    if (!$email) {
                         throw new CartException("Invalid email format.");
                    }
                    if (!$phone) {
                         throw new CartException("Invalid phone number. Must be 10-11 digits.");
                    }
                    if (!$name || !$address || !$book || !$quantity) {
                         throw new CartException("Invalid input data.");
                    }

                    // Lưu cookie
                    setcookie('customer_name', $name, time() + 7 * 24 * 3600, '/');
                    setcookie('customer_email', $email, time() + 7 * 24 * 3600, '/');

                    // Xử lý thông tin sách
                    list($title, $price) = explode('|', $book);
                    addToCart($title, $quantity, (int)$price);

                    // Lưu thông tin khách hàng vào session
                    $_SESSION['customer'] = [
                         'name' => $name,
                         'email' => $email,
                         'phone' => $phone,
                         'address' => $address
                    ];

                    echo '<div class="alert alert-success mt-3">Book added to cart successfully!</div>';
               } catch (CartException $e) {
                    echo '<div class="alert alert-danger mt-3">' . $e->getMessage() . '</div>';
               } catch (Exception $e) {
                    logError($e->getMessage());
                    echo '<div class="alert alert-danger mt-3">An unexpected error occurred. Please try again.</div>';
               }
          }
          ?>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>