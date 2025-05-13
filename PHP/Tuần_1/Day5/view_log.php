<?php
session_start();
if (!isset($_SESSION['user'])) {
     header('Location: login.php');
     exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>View Logs</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <?php include 'includes/header.php'; ?>
     <div class="container mt-5">
          <h1 class="mb-4">View Activity Logs</h1>
          <form action="view_log.php" method="get" class="mb-4">
               <div class="row g-3 align-items-center">
                    <div class="col-auto">
                         <label for="log_date" class="form-label">Select Date</label>
                    </div>
                    <div class="col-auto">
                         <input type="date" class="form-control" id="log_date" name="log_date" required value="<?php echo isset($_GET['log_date']) ? $_GET['log_date'] : ''; ?>">
                    </div>
                    <div class="col-auto">
                         <button type="submit" class="btn btn-primary">View Log</button>
                    </div>
               </div>
          </form>
          <?php
          if (isset($_GET['log_date'])) {
               $selected_date = $_GET['log_date'];
               $log_file = 'logs/log_' . $selected_date . '.txt';

               if (file_exists($log_file)) {
                    echo '<h3>Log for ' . $selected_date . '</h3>';
                    echo '<ul class="list-group">';
                    $file = fopen($log_file, 'r');
                    while (!feof($file)) {
                         $line = fgets($file);
                         if ($line) {
                              $color_class = '';
                              if (stripos($line, 'failed') !== false) {
                                   $color_class = 'list-group-item-danger';
                              } elseif (stripos($line, 'logged out') !== false) {
                                   $color_class = 'list-group-item-warning'; // Màu vàng cho đăng xuất
                              }
                              echo '<li class="list-group-item ' . $color_class . '">' . htmlspecialchars($line) . '</li>';
                         }
                    }
                    fclose($file);
                    echo '</ul>';
               } else {
                    echo '<div class="alert alert-info">No logs found for ' . $selected_date . '</div>';
               }
          }
          ?>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>