<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Activity Logger System</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <?php include 'includes/header.php'; ?>
     <div class="container mt-5">
          <h1 class="mb-4">User Activity Logger</h1>
          <div class="row">
               <div class="col-md-6">
                    <h3>Log an Action</h3>
                    <form action="index.php" method="post" enctype="multipart/form-data">
                         <div class="mb-3">
                              <label for="action" class="form-label">Action Description</label>
                              <input type="text" class="form-control" id="action" name="action" required>
                         </div>
                         <div class="mb-3">
                              <label for="file" class="form-label">Attach File (PDF, JPG, PNG)</label>
                              <input type="file" class="form-control" id="file" name="file">
                         </div>
                         <button type="submit" class="btn btn-primary" name="submit">Log Action</button>
                    </form>
                    <?php
                    if (isset($_POST['submit'])) {
                         require_once 'includes/logger.php';
                         require_once 'includes/upload.php';
                         $action = $_POST['action'];
                         $ip = $_SERVER['REMOTE_ADDR'];
                         $file_path = null;
                         if (!empty($_FILES['file']['name'])) {
                              $file_path = handleFileUpload();
                         }
                         logAction($action, $ip, $file_path);
                         echo '<div class="alert alert-success mt-3">Action logged successfully!</div>';
                    }
                    ?>
               </div>
               <div class="col-md-6">
                    <h3>View Logs</h3>
                    <form action="view_log.php" method="get">
                         <div class="mb-3">
                              <label for="log_date" class="form-label">Select Date</label>
                              <input type="date" class="form-control" id="log_date" name="log_date" required>
                         </div>
                         <button type="submit" class="btn btn-primary">View Log</button>
                    </form>
               </div>
          </div>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>