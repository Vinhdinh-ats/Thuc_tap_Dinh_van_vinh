package banking;

public class Main {
     public static void main(String[] args) throws InterruptedException {
          BankSystem bank = new BankSystem();

          // Tạo 100 tài khoản
          for (int i = 1; i <= 100; i++) {
               bank.addAccount(new BankAccount(i, (long) (Math.random() * 1_000_000)));
          }

          // 200 giao dịch đồng thời
          for (int i = 0; i < 200; i++) {
               int from = (int) (Math.random() * 100) + 1;
               int to = (int) (Math.random() * 100) + 1;
               long amount = (long) (Math.random() * 100_000);
               if (from != to) {
                    bank.transfer(from, to, amount);
               }
          }

          Thread.sleep(5000);

          bank.generateReport();

          Thread.sleep(3000);
          System.out.println("✅ Tong giao dich thanh cong: " + bank.getSuccessTransactionCount());

          bank.shutdown();
     }
}
