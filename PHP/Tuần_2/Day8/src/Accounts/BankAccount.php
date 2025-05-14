<?php

namespace XYZBank\Accounts;

use XYZBank\Database;
use XYZBank\Bank;

/**
 * Abstract base class for bank accounts.
 */
abstract class BankAccount
{
     protected string $accountNumber;
     protected string $ownerName;
     protected float $balance;

     public function __construct(string $accountNumber, string $ownerName, float $balance)
     {
          $this->accountNumber = $accountNumber;
          $this->ownerName = $ownerName;
          $this->balance = $balance;
          $this->saveToDatabase();
          Bank::incrementTotalAccounts();
     }

     public function getBalance(): float
     {
          return $this->balance;
     }

     public function getOwnerName(): string
     {
          return $this->ownerName;
     }

     public function getAccountNumber(): string
     {
          return $this->accountNumber;
     }

     abstract public function deposit(float $amount): void;
     abstract public function withdraw(float $amount): void;
     abstract public function getAccountType(): string;

     protected function updateBalance(float $newBalance): void
     {
          $this->balance = $newBalance;
          $this->saveToDatabase();
     }

     private function saveToDatabase(): void
     {
          $db = Database::getInstance()->getConnection();
          $stmt = $db->prepare('INSERT INTO accounts (account_number, owner_name, balance, account_type) VALUES (:account_number, :owner_name, :balance, :account_type) ON DUPLICATE KEY UPDATE balance = :balance, owner_name = :owner_name, account_type = :account_type');
          $stmt->execute([
               'account_number' => $this->accountNumber,
               'owner_name' => $this->ownerName,
               'balance' => $this->balance,
               'account_type' => $this->getAccountType()
          ]);
     }
}
