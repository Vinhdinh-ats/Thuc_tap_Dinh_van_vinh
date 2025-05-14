<?php

namespace XYZBank;

use XYZBank\Database;

/**
 * Utility class for bank-wide information.
 */
class Bank
{
     private static int $totalAccounts = 0;

     public static function getBankName(): string
     {
          return 'Ngân hàng XYZ';
     }

     public static function getTotalAccounts(): int
     {
          $db = Database::getInstance()->getConnection();
          $stmt = $db->query('SELECT COUNT(*) FROM accounts');
          return (int)$stmt->fetchColumn();
     }

     public static function incrementTotalAccounts(): void
     {
          self::$totalAccounts++;
     }
}
