<?php
$host = "localhost";
$db = "affiliate_db";
$user = "root";
$pass = "";

try {
     $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
     // Thiết lập chế độ báo lỗi
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     die("Lỗi kết nối: " . $e->getMessage());
}
