<?php

namespace XYZBank\Accounts;

use XYZBank\Database;
use IteratorAggregate;
use ArrayIterator;
use Traversable;

/**
 * Collection of bank accounts, iterable and filterable.
 */
class AccountCollection implements IteratorAggregate
{
     private array $accounts = [];

     public function __construct()
     {
          $this->loadAccounts();
     }

     private function loadAccounts(): void
     {
          $db = Database::getInstance()->getConnection();
          $stmt = $db->query('SELECT * FROM accounts');
          $accounts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($accounts as $data) {
               $accountClass = $data['account_type'] === 'Savings' ? SavingsAccount::class : CheckingAccount::class;
               $account = new $accountClass($data['account_number'], $data['owner_name'], (float)$data['balance']);
               $this->accounts[] = $account;
          }
     }

     public function addAccount(BankAccount $account): void
     {
          $this->accounts[] = $account;
     }

     public function getIterator(): Traversable
     {
          return new ArrayIterator($this->accounts);
     }

     public function filterHighBalance(float $threshold = 10000000): array
     {
          return array_filter($this->accounts, fn($account) => $account->getBalance() >= $threshold);
     }
}
