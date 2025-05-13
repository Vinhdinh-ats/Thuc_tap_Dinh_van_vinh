<?php
// Kết nối database (sử dụng PDO cho bảo mật và tiện mở rộng)
$pdo = new PDO("mysql:host=localhost;dbname=affiliate_db", "root", "");

// Lấy danh sách người dùng
$users = [];
foreach ($pdo->query("SELECT * FROM users") as $row) {
     $users[$row['id']] = ['name' => $row['name'], 'referrer_id' => $row['referrer_id']];
}

// Lấy danh sách đơn hàng
$orders = [];
foreach ($pdo->query("SELECT * FROM orders") as $row) {
     $orders[] = ['order_id' => $row['id'], 'user_id' => $row['user_id'], 'amount' => $row['amount']];
}

// Lấy tỷ lệ hoa hồng
$commissionRates = [];
foreach ($pdo->query("SELECT * FROM commission_levels") as $row) {
     $commissionRates[$row['level']] = $row['rate'];
}
define("MAX_LEVEL", 3);

// === Hàm logic xử lý hoa hồng như trước ===
function getReferrers(int $userId, array $users, int $level = 1): array
{
     static $referrers = [];
     if (!isset($users[$userId]['referrer_id']) || $level > MAX_LEVEL) return $referrers;

     $referrerId = $users[$userId]['referrer_id'];
     if ($referrerId !== null) {
          $referrers[$level] = $referrerId;
          getReferrers($referrerId, $users, $level + 1);
     }
     return $referrers;
}

function calculateCommission(array $orders, array $users, array $commissionRates): array
{
     $result = [];
     foreach ($orders as $order) {
          $buyerId = $order['user_id'];
          $amount = $order['amount'];
          $referrers = getReferrers($buyerId, $users);

          foreach ($referrers as $level => $referrerId) {
               $commission = $amount * ($commissionRates[$level] ?? 0);
               $result[$referrerId]['total'] = ($result[$referrerId]['total'] ?? 0) + $commission;
               $result[$referrerId]['details'][] = [
                    'from_order' => $order['order_id'],
                    'buyer' => $users[$buyerId]['name'],
                    'level' => $level,
                    'commission' => $commission,
               ];
          }
          getReferrers(0, [], 1); // reset static
     }
     return $result;
}

$commissions = calculateCommission($orders, $users, $commissionRates);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
     <meta charset="UTF-8">
     <title>Báo cáo hoa hồng</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">
     <div class="container">
          <h2 class="mb-4 text-primary">📊 Báo cáo hoa hồng</h2>

          <?php foreach ($commissions as $userId => $data): ?>
               <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                         <h5 class="mb-0">👤 <?= $users[$userId]['name'] ?> - Tổng hoa hồng: <strong><?= number_format($data['total'], 2) ?> USD</strong></h5>
                    </div>
                    <div class="card-body p-0">
                         <table class="table table-bordered mb-0">
                              <thead class="table-light">
                                   <tr>
                                        <th>Mã đơn</th>
                                        <th>Người mua</th>
                                        <th>Cấp</th>
                                        <th>Hoa hồng</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   <?php foreach ($data['details'] as $detail): ?>
                                        <tr>
                                             <td><?= $detail['from_order'] ?></td>
                                             <td><?= $detail['buyer'] ?></td>
                                             <td>Cấp <?= $detail['level'] ?></td>
                                             <td><?= number_format($detail['commission'], 2) ?> USD</td>
                                        </tr>
                                   <?php endforeach; ?>
                              </tbody>
                         </table>
                    </div>
               </div>
          <?php endforeach; ?>
     </div>
</body>

</html>