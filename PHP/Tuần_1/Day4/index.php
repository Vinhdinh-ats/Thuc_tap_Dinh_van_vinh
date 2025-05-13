<?php
require_once "db.php";  // Kết nối DB
require_once "config.php";  // Cấu hình DB
require_once "functions.php"; // Các hàm tiện ích

$error = "";
$success = "";
$warning = "";
$GLOBALS['total_income'] = 0;
$GLOBALS['total_expense'] = 0;

// Kiểm tra khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     $transaction_name = $_POST['transaction_name'] ?? '';
     $amount = $_POST['amount'] ?? '';
     $type = $_POST['type'] ?? '';
     $note = $_POST['note'] ?? '';
     $date = $_POST['date'] ?? '';

     $errors = [];

     // Kiểm tra tên không có ký tự đặc biệt
     if (!preg_match('/^[\p{L}0-9\s]+$/u', $transaction_name)) {
          $errors[] = "Tên giao dịch không được chứa ký tự đặc biệt.";
     }

     // Kiểm tra số tiền là số dương
     if (!is_numeric($amount) || $amount <= 0) {
          $errors[] = "Số tiền phải là số dương.";
     }

     // Kiểm tra định dạng ngày
     if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
          $errors[] = "Ngày phải theo định dạng dd/mm/yyyy.";
     }

     // Kiểm tra loại giao dịch
     if (empty($type)) {
          $errors[] = "Bạn chưa phân loại giao dịch (thu/chi).";
     }

     // Cảnh báo nếu ghi chú có từ nhạy cảm
     if (preg_match('/nợ xấu|vay nóng/i', $note)) {
          $warning = "Ghi chú chứa từ khóa nhạy cảm!";
     }

     if (empty($errors)) {
          // Chuyển đổi ngày từ dd/mm/yyyy sang yyyy-mm-dd để lưu vào DB
          $date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');

          // Insert dữ liệu vào database
          $stmt = $pdo->prepare("INSERT INTO transactions (transaction_name, amount, type, note, date) VALUES (?, ?, ?, ?, ?)");
          $stmt->execute([$transaction_name, $amount, $type, $note, $date]);

          // Tính tổng thu, chi và lưu vào $GLOBALS
          if ($type == 'thu') {
               $GLOBALS['total_income'] += $amount;
          } else {
               $GLOBALS['total_expense'] += $amount;
          }

          // Lưu cookie giao dịch gần nhất
          setcookie("last_transaction", $transaction_name, time() + 3600, "/");

          // Redirect để tránh gửi lại form khi F5
          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
     } else {
          $error = implode("<br>", $errors);
     }
}

// Lấy danh sách giao dịch từ DB
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY date DESC");
$transactions = $stmt->fetchAll();

// Tính tổng thu/chi từ database
foreach ($transactions as $tx) {
     if ($tx['type'] == "thu") {
          $GLOBALS['total_income'] += $tx['amount'];
     } else {
          $GLOBALS['total_expense'] += $tx['amount'];
     }
}

$balance = $GLOBALS['total_income'] - $GLOBALS['total_expense'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
     <meta charset="UTF-8">
     <title>Giao dịch tài chính</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body class="container py-5">
     <h2 class="mb-4">Nhập giao dịch tài chính</h2>

     <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
     <?php elseif ($success): ?>
          <div class="alert alert-success"><?= $success ?></div>
     <?php endif; ?>

     <?php if ($warning): ?>
          <div class="alert alert-warning"><?= $warning ?></div>
     <?php endif; ?>

     <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="row g-3 mb-4">
          <div class="col-md-6">
               <label class="form-label">Tên giao dịch</label>
               <input type="text" class="form-control" name="transaction_name" required>
          </div>
          <div class="col-md-6">
               <label class="form-label">Số tiền (VNĐ)</label>
               <input type="number" class="form-control" name="amount" step="0.01" required>
          </div>
          <div class="col-md-6">
               <label class="form-label">Loại giao dịch</label><br>
               <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" value="thu"> Thu
               </div>
               <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" value="chi"> Chi
               </div>
          </div>
          <div class="col-md-6">
               <label class="form-label">Ngày thực hiện</label>
               <input type="text" class="form-control" name="date" placeholder="dd/mm/yyyy" required>
          </div>
          <div class="col-12">
               <label class="form-label">Ghi chú</label>
               <textarea class="form-control" name="note" rows="2"></textarea>
          </div>
          <div class="col-12">
               <button class="btn btn-primary">Ghi nhận giao dịch</button>
          </div>
     </form>

     <h4>Lịch sử giao dịch</h4>
     <table class="table table-bordered">
          <thead class="table-light">
               <tr>
                    <th>#</th>
                    <th>Tên giao dịch</th>
                    <th>Số tiền</th>
                    <th>Loại</th>
                    <th>Ngày</th>
                    <th>Ghi chú</th>
               </tr>
          </thead>
          <tbody>
               <?php foreach ($transactions as $index => $t): ?>
                    <tr>
                         <td><?= $index + 1 ?></td>
                         <td><?= htmlspecialchars($t['transaction_name']) ?></td>
                         <td><?= number_format($t['amount']) ?> đ</td>
                         <td><?= strtoupper($t['type']) ?></td>
                         <td><?= date('d/m/Y', strtotime($t['date'])) ?></td>
                         <td><?= htmlspecialchars($t['note']) ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
     </table>

     <div class="alert alert-info mt-4">
          <p><strong>Tổng thu:</strong> <?= number_format($GLOBALS['total_income']) ?> VNĐ</p>
          <p><strong>Tổng chi:</strong> <?= number_format($GLOBALS['total_expense']) ?> VNĐ</p>
          <p><strong>Số dư:</strong> <?= number_format($balance) ?> VNĐ</p>
          <p><strong>Giao dịch gần nhất (COOKIE):</strong> <?= $_COOKIE['last_transaction'] ?? 'Không có' ?></p>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
     <script>
          flatpickr("input[name='date']", {
               dateFormat: "d/m/Y", // Định dạng dd/mm/yyyy
               allowInput: true
          });
     </script>
</body>

</html>