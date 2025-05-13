<?php
function logAction($action, $ip, $file_path = null)
{
     $date = date('Y-m-d');
     $log_dir = 'logs/';
     $log_file = $log_dir . "log_$date.txt";

     if (!file_exists($log_dir)) {
          mkdir($log_dir, 0777, true);
     }

     $timestamp = date('Y-m-d H:i:s');
     $log_entry = "[$timestamp] IP: $ip | Action: $action";
     if ($file_path) {
          $log_entry .= " | File: $file_path";
     }
     $log_entry .= PHP_EOL;

     file_put_contents($log_file, $log_entry, FILE_APPEND);
}
