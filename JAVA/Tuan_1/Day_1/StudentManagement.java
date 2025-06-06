import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Scanner;

// Lớp Student để lưu thông tin sinh viên
class Student {
     private int id;
     private String name;
     private int age;
     private double score;

     // Constructor
     public Student(int id, String name, int age, double score) {
          this.id = id;
          this.name = name;
          this.age = age;
          this.score = score;
     }

     // Getters
     public int getId() {
          return id;
     }

     public String getName() {
          return name;
     }

     public int getAge() {
          return age;
     }

     public double getScore() {
          return score;
     }

     @Override
     public String toString() {
          return "ID: " + id + ", Name: " + name + ", Age: " + age + ", Score: " + score;
     }
}

public class StudentManagement {
     private static ArrayList<Student> students = new ArrayList<>();
     private static int nextId = 1;
     private static Scanner scanner = new Scanner(System.in);

     public static void main(String[] args) {
          while (true) {
               displayMenu();
               int choice = scanner.nextInt();
               scanner.nextLine(); // Clear buffer

               switch (choice) {
                    case 1:
                         addStudent();
                         break;
                    case 2:
                         displayStudents();
                         break;
                    case 3:
                         searchStudentByName();
                         break;
                    case 4:
                         findTopStudent();
                         break;
                    case 5:
                         sortStudentsByScore();
                         break;
                    case 6:
                         calculateAverageScore();
                         break;
                    case 7:
                         calculateFactorialOfFirstStudentAge();
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
          System.out.println("\n=== QUAN LY SINH VIEN ===");
          System.out.println("1. Them sinh vien moi");
          System.out.println("2. Hien thi danh sach sinh vien");
          System.out.println("3. Tim sinh vien theo ten");
          System.out.println("4. Tim sinh vien co điem cao nhat");
          System.out.println("5. Sap xep theo điem giam dan");
          System.out.println("6. Tinh điem trung binh");
          System.out.println("7. Tinh giai thua tuoi sinh viên dau tiên");
          System.out.println("0. Thoat");
          System.out.print("Nhap lua chon: ");
     }

     // Thêm sinh viên mới
     private static void addStudent() {
          System.out.print("Nhập tên sinh viên: ");
          String name = scanner.nextLine();

          System.out.print("Nhập tuổi: ");
          int age = scanner.nextInt();
          if (age <= 0) {
               System.out.println("Tuổi phải lớn hơn 0!");
               return;
          }

          System.out.print("Nhập điểm: ");
          double score = scanner.nextDouble();
          if (score < 0 || score > 10) {
               System.out.println("Điểm phải từ 0 đến 10!");
               return;
          }

          Student student = new Student(nextId++, name, age, score);
          students.add(student);
          System.out.println("Thêm sinh viên thành công!");
     }

     // Hiển thị tất cả sinh viên
     private static void displayStudents() {
          if (students.isEmpty()) {
               System.out.println("Danh sách sinh viên rỗng!");
               return;
          }
          System.out.println("\nDanh sách sinh viên:");
          for (Student student : students) {
               printStudent(student);
          }
     }

     // Method overloading: In thông tin một sinh viên
     private static void printStudent(Student student) {
          System.out.println(student);
     }

     // Method overloading: In thông tin với tiêu đề
     private static void printStudent(Student student, String title) {
          System.out.println(title + ": " + student);
     }

     // Tìm sinh viên theo tên
     private static void searchStudentByName() {
          System.out.print("Nhập tên cần tìm: ");
          String searchName = scanner.nextLine().toLowerCase();
          boolean found = false;

          for (Student student : students) {
               if (student.getName().toLowerCase().contains(searchName)) {
                    printStudent(student, "Sinh viên tìm thấy");
                    found = true;
               }
          }

          if (!found) {
               System.out.println("Không tìm thấy sinh viên nào!");
          }
     }

     // Tìm sinh viên có điểm cao nhất
     private static void findTopStudent() {
          if (students.isEmpty()) {
               System.out.println("Danh sách sinh viên rỗng!");
               return;
          }

          Student topStudent = Collections.max(students, Comparator.comparingDouble(Student::getScore));
          printStudent(topStudent, "Sinh viên có điểm cao nhất");
     }

     // Sắp xếp sinh viên theo điểm giảm dần
     private static void sortStudentsByScore() {
          if (students.isEmpty()) {
               System.out.println("Danh sách sinh viên rỗng!");
               return;
          }

          students.sort(Comparator.comparingDouble(Student::getScore).reversed());
          System.out.println("Đã sắp xếp theo điểm giảm dần!");
          displayStudents();
     }

     // Tính điểm trung bình
     private static void calculateAverageScore() {
          if (students.isEmpty()) {
               System.out.println("Danh sách sinh viên rỗng!");
               return;
          }

          double sum = 0;
          for (Student student : students) {
               sum += student.getScore();
          }
          double average = sum / students.size();
          System.out.printf("Điểm trung bình: %.2f\n", average);
     }

     // Tính giai thừa tuổi sinh viên đầu tiên (đệ quy)
     private static void calculateFactorialOfFirstStudentAge() {
          if (students.isEmpty()) {
               System.out.println("Danh sách sinh viên rỗng!");
               return;
          }

          int age = students.get(0).getAge();
          long factorial = calculateFactorial(age);
          System.out.println("Giai thừa của tuổi sinh viên đầu tiên (" + age + ") là: " + factorial);
     }

     // Hàm đệ quy tính giai thừa
     private static long calculateFactorial(int n) {
          if (n <= 1) {
               return 1;
          }
          return n * calculateFactorial(n - 1);
     }
}