package banking;

import java.util.concurrent.locks.ReentrantLock;

public class BankAccount {
     private final int id;
     private long balance;
     private final ReentrantLock lock = new ReentrantLock();

     public BankAccount(int id, long initialBalance) {
          this.id = id;
          this.balance = initialBalance;
     }

     public boolean withdraw(long amount) throws InterruptedException {
          lock.lock();
          try {
               while (balance < amount) {
                    lock.unlock();
                    Thread.sleep(100); // Chờ thêm tiền
                    lock.lock();
               }
               balance -= amount;
               return true;
          } finally {
               lock.unlock();
          }
     }

     public void deposit(long amount) {
          lock.lock();
          try {
               balance += amount;
          } finally {
               lock.unlock();
          }
     }

     public long getBalance() {
          return balance;
     }

     public int getId() {
          return id;
     }

     public ReentrantLock getLock() {
          return lock;
     }
}
