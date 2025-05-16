<?php
$host = 'localhost';
$dbname = 'shop_db';
$user = 'root';
$pass = '';

try {
     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
     die("Lỗi kết nối CSDL: " . $e->getMessage());
}
