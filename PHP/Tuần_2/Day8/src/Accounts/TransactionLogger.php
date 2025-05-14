<?php

namespace XYZBank\Accounts;

use XYZBank\Database;

/**
 * Trait for logging transactions.
 */
trait TransactionLogger
{
     public function logTransaction(string $type, float $amount, float $newBalance): void
     {
          $db = Database::getInstance()->getConnection();
          $stmt = $db->prepare('INSERT INTO transaction_logs (account_number, transaction_type, amount, new_balance) VALUES (:account_number, :transaction_type, :amount, :new_balance)');
          $stmt->execute([
               'account_number' => $this->accountNumber,
               'transaction_type' => $type,
               'amount' => $amount,
               'new_balance' => $newBalance
          ]);

          echo sprintf(
               "[%s] Giao dịch: %s %.2f VNĐ | Số dư mới: %.2f VNĐ<br>",
               date('Y-m-d H:i:s'),
               $type,
               $amount,
               $newBalance
          );
     }
}
