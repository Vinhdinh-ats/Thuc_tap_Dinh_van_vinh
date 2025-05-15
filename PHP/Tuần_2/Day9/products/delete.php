<link href="../assets/bootstrap.min.css" rel="stylesheet">
<?php require '../db.php';
$id = $_GET['id'];
$pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
header("Location: list.php"); ?>