<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <?php include 'includes/header.php'; ?>
     <div class="container mt-5">
          <h1 class="mb-4">Login</h1>
          <form action="login.php" method="post">
               <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
               </div>
               <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
               </div>
               <button type="submit" class="btn btn-primary" name="submit">Login</button>
          </form>
          <?php
          session_start();
          require_once 'includes/logger.php';
          if (isset($_POST['submit'])) {
               $username = $_POST['username'];
               $password = $_POST['password'];
               $ip = $_SERVER['REMOTE_ADDR'];

               // Giả sử kiểm tra đăng nhập đơn giản (thay bằng cơ chế thực tế)
               if ($username === 'admin' && $password === 'password123') {
                    $_SESSION['user'] = $username;
                    logAction("User $username logged in", $ip);
                    header('Location: index.php');
                    exit;
               } else {
                    logAction("Login failed for $username", $ip);
                    echo '<div class="alert alert-danger mt-3">Invalid credentials!</div>';
               }
          }
          ?>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>