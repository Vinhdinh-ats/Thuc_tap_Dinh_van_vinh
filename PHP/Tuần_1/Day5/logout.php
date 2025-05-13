<?php
session_start();
require_once 'includes/logger.php';

// Ghi log hành động đăng xuất
$ip = $_SERVER['REMOTE_ADDR'];
$username = isset($_SESSION['user']) ? $_SESSION['user'] : 'Unknown';
logAction("User $username logged out", $ip);

// Hủy session
session_unset();
session_destroy();

// Chuyển hướng về trang chủ
header('Location: index.php');
exit;
