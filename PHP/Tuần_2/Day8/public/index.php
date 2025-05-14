<?php
require_once __DIR__ . '/../vendor/autoload.php';

use XYZBank\Accounts\SavingsAccount;
use XYZBank\Accounts\CheckingAccount;
use XYZBank\Accounts\AccountCollection;
use XYZBank\Bank;

$collection = new AccountCollection();

// Create test accounts
$savings = new SavingsAccount('10201122', 'Nguyễn Thị A', 20000000);
$checking1 = new CheckingAccount('20301123', 'Lê Văn B', 8000000);
$checking2 = new CheckingAccount('20401124', 'Trần Minh C', 12000000);

// Perform transactions
$checking1->deposit(5000000);
$checking2->withdraw(2000000);

// Calculate interest
$interest = $savings->calculateAnnualInterest();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Ngân hàng XYZ - Quản lý tài khoản</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
     <div class="container mt-5">
          <h1 class="mb-4"><?php echo Bank::getBankName(); ?></h1>

          <h2>Nhật ký giao dịch</h2>
          <div class="mb-4">
               <?php
               // Transaction logs are output by TransactionLogger
               ?>
          </div>

          <h2>Danh sách tài khoản</h2>
          <table class="table table-striped">
               <thead>
                    <tr>
                         <th>Mã số tài khoản</th>
                         <th>Chủ tài khoản</th>
                         <th>Loại tài khoản</th>
                         <th>Số dư</th>
                    </tr>
               </thead>
               <tbody>
                    <?php foreach ($collection as $account): ?>
                         <tr>
                              <td><?php echo htmlspecialchars($account->getAccountNumber()); ?></td>
                              <td><?php echo htmlspecialchars($account->getOwnerName()); ?></td>
                              <td><?php echo htmlspecialchars($account->getAccountType() === 'Savings' ? 'Tiết kiệm' : 'Thanh toán'); ?></td>
                              <td><?php echo number_format($account->getBalance(), 0, ',', '.') . ' VNĐ'; ?></td>
                         </tr>
                    <?php endforeach; ?>
               </tbody>
          </table>

          <h2>Thông tin bổ sung</h2>
          <ul class="list-group mb-4">
               <li class="list-group-item">Lãi suất hàng năm cho Nguyễn Thị A: <?php echo number_format($interest, 0, ',', '.') . ' VNĐ'; ?></li>
               <li class="list-group-item">Tổng số tài khoản đã tạo: <?php echo Bank::getTotalAccounts(); ?></li>
               <li class="list-group-item">Tên ngân hàng: <?php echo Bank::getBankName(); ?></li>
          </ul>

          <h2>Tài khoản có số dư ≥ 10.000.000 VNĐ</h2>
          <table class="table table-striped">
               <thead>
                    <tr>
                         <th>Mã số tài khoản</th>
                         <th>Chủ tài khoản</th>
                         <th>Loại tài khoản</th>
                         <th>Số dư</th>
                    </tr>
               </thead>
               <tbody>
                    <?php foreach ($collection->filterHighBalance() as $account): ?>
                         <tr>
                              <td><?php echo htmlspecialchars($account->getAccountNumber()); ?></td>
                              <td><?php echo htmlspecialchars($account->getOwnerName()); ?></td>
                              <td><?php echo htmlspecialchars($account->getAccountType() === 'Savings' ? 'Tiết kiệm' : 'Thanh toán'); ?></td>
                              <td><?php echo number_format($account->getBalance(), 0, ',', '.') . ' VNĐ'; ?></td>
                         </tr>
                    <?php endforeach; ?>
               </tbody>
          </table>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>