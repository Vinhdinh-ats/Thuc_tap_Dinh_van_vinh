using PayrollSystem;

class Program
{
     static void Main(string[] args)
     {
          // Tạo một repository giả để test
          var repo = new FakeEmployeeRepository();
          var service = new PayrollService(repo);

          // Tính lương cho nhân viên với ID = 1
          try
          {
               decimal salary = service.GetNetSalary(1);
               Console.WriteLine($"Net Salary: {salary}");
          }
          catch (Exception ex)
          {
               Console.WriteLine($"Error: {ex.Message}");
          }
     }
}

// Repository giả để test
class FakeEmployeeRepository : IEmployeeRepository
{
     public Employee GetById(int id)
     {
          return new Employee
          {
               Id = id,
               Name = "Nguyen Van A",
               BaseSalary = 1000m,
               Bonus = 200m,
               Deduction = 100m
          };
     }
}