<?php
$host = "localhost";
$dbname = "affiliate_db1";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
     die("Kết nối thất bại: " . $conn->connect_error);
}

const COMMISSION_RATE = 0.2;
const VAT_RATE = 0.1;

$sql_campaign = "SELECT * FROM chiendich ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql_campaign);

$campaign_data = null;
$order_list = [];

if ($result->num_rows > 0) {
     $chien_dich = $result->fetch_assoc();

     $ten_chien_dich = $chien_dich['ten_chien_dich'];
     $gia_san_pham = (float)$chien_dich['gia_san_pham'];
     $so_luong_don = (int)$chien_dich['so_luong_don'];
     $loai_san_pham = $chien_dich['loai_san_pham'];
     $trang_thai = (bool)$chien_dich['trang_thai'];

     $sql_orders = "SELECT * FROM don_hang WHERE id_chien_dich = " . $chien_dich['id'];
     $orders = $conn->query($sql_orders);

     $tong_doanh_thu = 0;
     while ($row = $orders->fetch_assoc()) {
          $tong_doanh_thu += (float)$row['gia_tri'];
          $order_list[] = $row;
     }

     $chi_phi_hoa_hong = $tong_doanh_thu * COMMISSION_RATE;
     $thue_vat = $tong_doanh_thu * VAT_RATE;
     $loi_nhuan = $tong_doanh_thu - $chi_phi_hoa_hong - $thue_vat;

     if ($loi_nhuan > 0) $danh_gia = "Chiến dịch thành công";
     elseif ($loi_nhuan == 0) $danh_gia = "Chiến dịch hòa vốn";
     else $danh_gia = "Chiến dịch thất bại";

     switch ($loai_san_pham) {
          case "Điện tử":
               $goi_y = "Sản phẩm điện tử có biên lợi nhuận cao.";
               break;
          case "Thời trang":
               $goi_y = "Sản phẩm Thời trang có doanh thu ổn định.";
               break;
          case "Gia dụng":
               $goi_y = "Sản phẩm gia dụng phù hợp với gia đình.";
               break;
          default:
               $goi_y = "Loại sản phẩm không xác định.";
     }

     $campaign_data = [
          "ten" => $ten_chien_dich,
          "trang_thai" => $trang_thai,
          "tong_doanh_thu" => $tong_doanh_thu,
          "hoa_hong" => $chi_phi_hoa_hong,
          "vat" => $thue_vat,
          "loi_nhuan" => $loi_nhuan,
          "danh_gia" => $danh_gia,
          "goi_y" => $goi_y
     ];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
     <meta charset="UTF-8">
     <title>Phân tích Affiliate Marketing</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

     <div class="container mt-5">
          <h2 class="mb-4 text-center">Báo cáo chiến dịch Affiliate Marketing</h2>

          <?php if ($campaign_data): ?>
               <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                         <h4><?= $campaign_data["ten"] ?> (<?= $campaign_data["trang_thai"] ? "Đã kết thúc" : "Đang chạy" ?>)</h4>
                    </div>
                    <div class="card-body">
                         <p><strong>Tổng doanh thu:</strong> $<?= number_format($campaign_data["tong_doanh_thu"], 2) ?></p>
                         <p><strong>Chi phí hoa hồng:</strong> $<?= number_format($campaign_data["hoa_hong"], 2) ?></p>
                         <p><strong>Thuế VAT:</strong> $<?= number_format($campaign_data["vat"], 2) ?></p>
                         <p><strong>Lợi nhuận:</strong> $<?= number_format($campaign_data["loi_nhuan"], 2) ?></p>
                         <p><strong>Đánh giá:</strong> <?= $campaign_data["danh_gia"] ?></p>
                         <p><strong>Gợi ý:</strong> <?= $campaign_data["goi_y"] ?></p>
                    </div>
               </div>

               <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                         <h5>Chi tiết đơn hàng</h5>
                    </div>
                    <div class="card-body p-0">
                         <table class="table table-bordered table-hover mb-0">
                              <thead class="table-light">
                                   <tr>
                                        <th>ID</th>
                                        <th>Mã đơn</th>
                                        <th>Giá trị</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   <?php foreach ($order_list as $index => $order): ?>
                                        <tr>
                                             <td><?= $index + 1 ?></td>
                                             <td><?= $order['ma_don'] ?></td>
                                             <td>$<?= number_format($order['gia_tri'], 2) ?></td>
                                        </tr>
                                   <?php endforeach; ?>
                              </tbody>
                         </table>
                    </div>
               </div>

               <div class="alert alert-success mt-4">
                    <strong>Thông báo:</strong> Chiến dịch <strong><?= $campaign_data["ten"] ?></strong> đã <?= $campaign_data["trang_thai"] ? "kết thúc" : "chưa kết thúc" ?> với lợi nhuận: <strong>$<?= number_format($campaign_data["loi_nhuan"], 2) ?> USD</strong>.
               </div>
          <?php else: ?>
               <div class="alert alert-warning">Không có chiến dịch nào được tìm thấy trong cơ sở dữ liệu.</div>
          <?php endif; ?>
     </div>

</body>

</html>