<?php
header('Content-Type: text/html; charset=UTF-8');

if (!isset($_GET['category'])) {
     echo '<option value="">-- Chọn thương hiệu --</option>';
     exit;
}

$category = trim($_GET['category']);
$xml = simplexml_load_file('brands.xml');
$options = '';

foreach ($xml->category as $cat) {
     $catName = trim((string)$cat['name']);
     if ($catName === $category) {
          foreach ($cat->brand as $brand) {
               $brandName = htmlspecialchars(trim((string)$brand));
               $options .= "<option value=\"$brandName\">$brandName</option>";
          }
          break;
     }
}

if ($options === '') {
     $options = '<option value="">-- Không có thương hiệu nào --</option>';
}

echo $options;
