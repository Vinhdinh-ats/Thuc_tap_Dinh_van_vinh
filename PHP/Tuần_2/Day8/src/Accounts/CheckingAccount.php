<?php

namespace XYZBank\Accounts;

/**
 * Checking account with no interest or minimum balance.
 */
class CheckingAccount extends BankAccount
{
     use TransactionLogger;

     public function deposit(float $amount): void
     {
          if ($amount <= 0) {
               throw new \InvalidArgumentException("Số tiền gửi phải lớn hơn 0");
          }
          $newBalance = $this->balance + $amount;
          $this->updateBalance($newBalance);
          $this->logTransaction('Gửi tiền', $amount, $newBalance);
     }

     public function withdraw(float $amount): void
     {
          if ($amount <= 0) {
               throw new \InvalidArgumentException("Số tiền rút phải lớn hơn 0");
          }
          if ($amount > $this->balance) {
               throw new \RuntimeException("Số dư không đủ");
          }
          $this->updateBalance($newBalance = $this->balance - $amount);
          $this->logTransaction('Rút tiền', $amount, $newBalance);
     }

     public function getAccountType(): string
     {
          return 'Checking';
     }
}
