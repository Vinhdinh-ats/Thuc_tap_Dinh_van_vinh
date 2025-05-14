<?php

namespace XYZBank\Accounts;

/**
 * Savings account with interest and minimum balance requirements.
 */
class SavingsAccount extends BankAccount implements InterestBearing
{
     use TransactionLogger;

     private const INTEREST_RATE = 0.05;
     private const MINIMUM_BALANCE = 1000000;

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
          $newBalance = $this->balance - $amount;
          if ($newBalance < self::MINIMUM_BALANCE) {
               throw new \RuntimeException("Số dư sau rút phải >= 1.000.000 VNĐ");
          }
          $this->updateBalance($newBalance);
          $this->logTransaction('Rút tiền', $amount, $newBalance);
     }

     public function getAccountType(): string
     {
          return 'Savings';
     }

     public function calculateAnnualInterest(): float
     {
          return $this->balance * self::INTEREST_RATE;
     }
}
