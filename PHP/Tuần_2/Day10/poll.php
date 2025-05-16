<?php
// poll.php
header('Content-Type: application/json');

// Tên file lưu trữ dữ liệu
$file = 'poll_data.json';

// Các tùy chọn hợp lệ
$options = ['Giao diện', 'Tốc độ', 'Dịch vụ khách hàng'];

// Nếu chưa có file, khởi tạo dữ liệu rỗng
if (!file_exists($file)) {
     $initData = array_fill_keys($options, 0);
     file_put_contents($file, json_encode($initData));
}

// Lấy dữ liệu hiện tại
$data = json_decode(file_get_contents($file), true);

// Nhận bình chọn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
     $vote = $_POST['vote'];
     if (in_array($vote, $options)) {
          $data[$vote]++;
          file_put_contents($file, json_encode($data));
     }
}

// Tổng số phiếu
$totalVotes = array_sum($data);

// Trả về JSON
echo json_encode([
     'results' => $data,
     'total' => $totalVotes
]);
