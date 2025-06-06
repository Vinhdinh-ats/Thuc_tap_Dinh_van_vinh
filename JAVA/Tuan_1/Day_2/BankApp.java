import java.time.LocalDate;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.Scanner;

// Lớp Person lưu thông tin khách hàng
class Person {
    private String id;
    private String fullName;
    private String email;
    private String phoneNumber;

    // Constructor
    public Person(String id, String fullName, String email, String phoneNumber) {
        this.id = id;
        this.fullName = fullName;
        this.email = email;
        this.phoneNumber = phoneNumber;
    }

    // Getters và Setters
    public String getId() { return id; }
    public void setId(String id) { this.id = id; }
    public String getFullName() { return fullName; }
    public void setFullName(String fullName) { this.fullName = fullName; }
    public String getEmail() { return email; }
    public void setEmail(String email) { this.email = email; }
    public String getPhoneNumber() { return phoneNumber; }
    public void setPhoneNumber(String phoneNumber) { this.phoneNumber = phoneNumber; }

    @Override
    public String toString() {
        return "ID: " + id + ", Name: " + fullName + ", Email: " + email + ", Phone: " + phoneNumber;
    }
}

// Enum cho loại tài khoản
enum AccountType {
    SAVINGS, CURRENT
}

// Enum cho loại giao dịch
enum TransactionType {
    DEPOSIT, WITHDRAW
}

// Interface Printable
interface Printable {
    void printSummary();
}

// Lớp trừu tượng BankAccount
abstract class BankAccount implements Printable {
    protected String accountNumber;
    protected Person owner;
    protected double balance;
    protected LocalDate createdDate;
    protected ArrayList<Transaction> transactions;

    // Inner class Transaction
    protected class Transaction {
        private String id;
        private TransactionType type;
        private double amount;
        private LocalDateTime timestamp;

        public Transaction(String id, TransactionType type, double amount, LocalDateTime timestamp) {
            this.id = id;
            this.type = type;
            this.amount = amount;
            this.timestamp = timestamp;
        }

        @Override
        public String toString() {
            return "Transaction ID: " + id + ", Type: " + type + ", Amount: " + amount + ", Time: " + timestamp;
        }
    }

    // Constructor
    public BankAccount(String accountNumber, Person owner, double initialBalance, LocalDate createdDate) {
        this.accountNumber = accountNumber;
        this.owner = owner;
        this.balance = initialBalance;
        this.createdDate = createdDate;
        this.transactions = new ArrayList<>();
    }

    // Getters
    public String getAccountNumber() { return accountNumber; }
    public Person getOwner() { return owner; }
    public double getBalance() { return balance; }
    public LocalDate getCreatedDate() { return createdDate; }

    // Phương thức gửi tiền
    public void deposit(double amount, String transactionId) {
        if (amount > 0) {
            balance += amount;
            transactions.add(new Transaction(transactionId, TransactionType.DEPOSIT, amount, LocalDateTime.now()));
            System.out.println("Gửi tiền thành công! Số dư mới: " + balance);
        } else {
            System.out.println("Số tiền gửi phải lớn hơn 0!");
        }
    }

    // Phương thức trừu tượng rút tiền
    public abstract void withdraw(double amount, String transactionId);

    // Phương thức in thông tin tài khoản
    public void printAccountInfo() {
        System.out.println("Account Number: " + accountNumber);
        System.out.println("Owner: " + owner.getFullName());
        System.out.println("Balance: " + balance);
        System.out.println("Created Date: " + createdDate);
    }

    // Triển khai Printable
    @Override
    public void printSummary() {
        printAccountInfo();
        System.out.println("Transaction History:");
        if (transactions.isEmpty()) {
            System.out.println("No transactions.");
        } else {
            for (Transaction transaction : transactions) {
                System.out.println(transaction);
            }
        }
    }
}

// Lớp SavingsAccount kế thừa BankAccount
class SavingsAccount extends BankAccount {
    private double interestRate;

    public SavingsAccount(String accountNumber, Person owner, double initialBalance, LocalDate createdDate, double interestRate) {
        super(accountNumber, owner, initialBalance, createdDate);
        this.interestRate = interestRate;
    }

    @Override
    public void withdraw(double amount, String transactionId) {
        if (amount > 0 && balance - amount >= 1000) { // Giới hạn số dư tối thiểu 1000
            balance -= amount;
            transactions.add(new Transaction(transactionId, TransactionType.WITHDRAW, amount, LocalDateTime.now()));
            System.out.println("Rút tiền thành công! Số dư mới: " + balance);
        } else {
            System.out.println("Số dư không đủ hoặc số tiền rút không hợp lệ!");
        }
    }

    @Override
    public void printAccountInfo() {
        super.printAccountInfo();
        System.out.println("Account Type: SAVINGS");
        System.out.println("Interest Rate: " + interestRate + "%");
    }
}

// Lớp CurrentAccount kế thừa BankAccount
class CurrentAccount extends BankAccount {
    public CurrentAccount(String accountNumber, Person owner, double initialBalance, LocalDate createdDate) {
        super(accountNumber, owner, initialBalance, createdDate);
    }

    @Override
    public void withdraw(double amount, String transactionId) {
        if (amount > 0 && balance >= amount) { // Chỉ cần đủ số dư
            balance -= amount;
            transactions.add(new Transaction(transactionId, TransactionType.WITHDRAW, amount, LocalDateTime.now()));
            System.out.println("Rút tiền thành công! Số dư mới: " + balance);
        } else {
            System.out.println("Số dư không đủ hoặc số tiền rút không hợp lệ!");
        }
    }

    @Override
    public void printAccountInfo() {
        super.printAccountInfo();
        System.out.println("Account Type: CURRENT");
    }
}

// Lớp chính BankApp
public class BankApp {
    private static ArrayList<BankAccount> accounts = new ArrayList<>();
    private static Scanner scanner = new Scanner(System.in);

    public static void main(String[] args) {
        while (true) {
            displayMenu();
            int choice = scanner.nextInt();
            scanner.nextLine(); // Clear buffer

            switch (choice) {
                case 1:
                    createAccount();
                    break;
                case 2:
                    depositMoney();
                    break;
                case 3:
                    withdrawMoney();
                    break;
                case 4:
                    viewAccountInfo();
                    break;
                case 0:
                    System.out.println("Thoát chương trình!");
                    return;
                default:
                    System.out.println("Lựa chọn không hợp lệ!");
            }
        }
    }

    // Hiển thị menu
    private static void displayMenu() {
        System.out.println("\n=== HỆ THỐNG QUẢN LÝ NGÂN HÀNG ===");
        System.out.println("1. Tạo tài khoản mới");
        System.out.println("2. Gửi tiền");
        System.out.println("3. Rút tiền");
        System.out.println("4. Xem thông tin tài khoản");
        System.out.println("0. Thoát");
        System.out.print("Nhập lựa chọn: ");
    }

    // Tạo tài khoản mới
    private static void createAccount() {
        System.out.print("Nhập ID khách hàng: ");
        String id = scanner.nextLine();
        System.out.print("Nhập họ tên: ");
        String fullName = scanner.nextLine();
        System.out.print("Nhập email: ");
        String email = scanner.nextLine();
        System.out.print("Nhập số điện thoại: ");
        String phoneNumber = scanner.nextLine();

        Person owner = new Person(id, fullName, email, phoneNumber);

        System.out.print("Nhập số tài khoản: ");
        String accountNumber = scanner.nextLine();
        System.out.print("Nhập số dư ban đầu: ");
        double initialBalance = scanner.nextDouble();
        scanner.nextLine(); // Clear buffer

        System.out.print("Chọn loại tài khoản (1: Tiết kiệm, 2: Thanh toán): ");
        int accountType = scanner.nextInt();
        scanner.nextLine(); // Clear buffer

        BankAccount account;
        if (accountType == 1) {
            System.out.print("Nhập lãi suất (%): ");
            double interestRate = scanner.nextDouble();
            scanner.nextLine(); // Clear buffer
            account = new SavingsAccount(accountNumber, owner, initialBalance, LocalDate.now(), interestRate);
        } else {
            account = new CurrentAccount(accountNumber, owner, initialBalance, LocalDate.now());
        }

        accounts.add(account);
        System.out.println("Tạo tài khoản thành công!");
    }

    // Gửi tiền
    private static void depositMoney() {
        System.out.print("Nhập số tài khoản: ");
        String accountNumber = scanner.nextLine();
        BankAccount account = findAccount(accountNumber);
        if (account == null) {
            System.out.println("Tài khoản không tồn tại!");
            return;
        }

        System.out.print("Nhập số tiền gửi: ");
        double amount = scanner.nextDouble();
        scanner.nextLine(); // Clear buffer
        System.out.print("Nhập ID giao dịch: ");
        String transactionId = scanner.nextLine();

        account.deposit(amount, transactionId);
    }

    // Rút tiền
    private static void withdrawMoney() {
        System.out.print("Nhập số tài khoản: ");
        String accountNumber = scanner.nextLine();
        BankAccount account = findAccount(accountNumber);
        if (account == null) {
            System.out.println("Tài khoản không tồn tại!");
            return;
        }

        System.out.print("Nhập số tiền rút: ");
        double amount = scanner.nextDouble();
        scanner.nextLine(); // Clear buffer
        System.out.print("Nhập ID giao dịch: ");
        String transactionId = scanner.nextLine();

        account.withdraw(amount, transactionId);
    }

    // Xem thông tin tài khoản
    private static void viewAccountInfo() {
        System.out.print("Nhập số tài khoản: ");
        String accountNumber = scanner.nextLine();
        BankAccount account = findAccount(accountNumber);
        if (account == null) {
            System.out.println("Tài khoản không tồn tại!");
            return;
        }

        account.printSummary();
    }

    // Tìm tài khoản theo số tài khoản
    private static BankAccount findAccount(String accountNumber) {
        for (BankAccount account : accounts) {
            if (account.getAccountNumber().equals(accountNumber)) {
                return account;
            }
        }
        return null;
    }
}