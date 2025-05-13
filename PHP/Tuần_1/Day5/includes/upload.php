<?php
function handleFileUpload()
{
     $upload_dir = 'uploads/';
     $max_size = 2 * 1024 * 1024; // 2MB
     $allowed_types = ['jpg', 'png', 'pdf'];

     if (!file_exists($upload_dir)) {
          mkdir($upload_dir, 0777, true);
     }

     $file = $_FILES['file'];
     $file_name = $file['name'];
     $file_tmp = $file['tmp_name'];
     $file_size = $file['size'];
     $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

     if ($file_size > $max_size) {
          die('<div class="alert alert-danger">File size exceeds 2MB limit!</div>');
     }

     if (!in_array($file_ext, $allowed_types)) {
          die('<div class="alert alert-danger">Invalid file type! Only JPG, PNG, PDF allowed.</div>');
     }

     $new_file_name = 'upload_' . time() . '_' . uniqid() . '.' . $file_ext;
     $destination = $upload_dir . $new_file_name;

     if (move_uploaded_file($file_tmp, $destination)) {
          return $destination;
     } else {
          die('<div class="alert alert-danger">File upload failed!</div>');
     }
}
