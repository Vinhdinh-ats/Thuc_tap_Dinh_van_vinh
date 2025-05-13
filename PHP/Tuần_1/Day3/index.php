<?php
require 'payroll.php';
list($payrollData, $total) = getEmployeePayrollData($conn);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
     <meta charset="UTF-8">
     <title>Bảng Lương Tháng</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
     <h2 class="mb-4">📄 Bảng Lương Tháng 03/2025</h2>
     <table class="table table-bordered table-hover">
          <thead class="table-dark">
               <tr>
                    <th>Mã NV</th>
                    <th>Họ tên</th>
                    <th>Ngày công</th>
                    <th>Lương cơ bản</th>
                    <th>Phụ cấp</th>
                    <th>Khấu trừ</th>
                    <th>Lương thực lĩnh</th>
               </tr>
          </thead>
          <tbody>
               <?php foreach ($payrollData as $row): ?>
                    <tr>
                         <td><?= $row['id'] ?></td>
                         <td><?= $row['name'] ?></td>
                         <td><?= $row['work_days'] ?></td>
                         <td><?= number_format($row['base_salary']) ?> VND</td>
                         <td><?= number_format($row['allowance']) ?> VND</td>
                         <td><?= number_format($row['deduction']) ?> VND</td>
                         <td><strong><?= number_format($row['actual_salary']) ?> VND</strong></td>
                    </tr>
               <?php endforeach ?>
          </tbody>
     </table>

     <div class="mt-4 alert alert-info">
          💰 <strong>Tổng quỹ lương tháng:</strong> <?= number_format($total) ?> VND
     </div>
</body>

</html>