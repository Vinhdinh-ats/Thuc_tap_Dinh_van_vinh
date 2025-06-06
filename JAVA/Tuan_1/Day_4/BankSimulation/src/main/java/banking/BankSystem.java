package banking;

import java.util.*;
import java.util.concurrent.*;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.stream.Collectors;

public class BankSystem {
     private final Map<Integer, BankAccount> accounts = new ConcurrentHashMap<>();
     private final Semaphore transactionLimiter = new Semaphore(10);
     private final AtomicInteger successCount = new AtomicInteger(0);
     private final ExecutorService executor = Executors.newFixedThreadPool(100);
     private final ExecutorService reportExecutor = Executors.newCachedThreadPool();

     public void addAccount(BankAccount acc) {
          accounts.put(acc.getId(), acc);
     }

     public void transfer(int fromId, int toId, long amount) {
          executor.submit(() -> {
               try {
                    transactionLimiter.acquire();

                    BankAccount from = accounts.get(fromId);
                    BankAccount to = accounts.get(toId);

                    List<BankAccount> locks = Arrays.asList(from, to);
                    locks.sort(Comparator.comparingInt(BankAccount::getId));

                    locks.get(0).getLock().lock();
                    locks.get(1).getLock().lock();

                    try {
                         if (from.withdraw(amount)) {
                              to.deposit(amount);
                              successCount.incrementAndGet();
                              CompletableFuture.runAsync(() -> {
                                   System.out.println("📩 Email xác nhan gui cho nguoi dung " + fromId);
                              });
                         }
                    } finally {
                         locks.get(1).getLock().unlock();
                         locks.get(0).getLock().unlock();
                    }
               } catch (InterruptedException e) {
                    e.printStackTrace();
               } finally {
                    transactionLimiter.release();
               }
          });
     }

     public void generateReport() {
          reportExecutor.submit(() -> {
               System.out.println("===== BAO CAO =====");
               long total = accounts.values().parallelStream().mapToLong(BankAccount::getBalance).sum();
               System.out.println("Tong so tien trong ngan hang: " + total);

               List<BankAccount> richAccounts = accounts.values()
                         .parallelStream()
                         .filter(acc -> acc.getBalance() > 1_000_000)
                         .collect(Collectors.toList());

               richAccounts.forEach(acc -> System.out
                         .println("⚠️ Tai khoan du cao: ID=" + acc.getId() + ", Balance=" + acc.getBalance()));
          });
     }

     public int getSuccessTransactionCount() {
          return successCount.get();
     }

     public void shutdown() {
          executor.shutdown();
          reportExecutor.shutdown();
     }
}
