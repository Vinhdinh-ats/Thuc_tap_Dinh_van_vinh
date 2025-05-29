namespace PayrollSystem;

public class PayrollService
{
     private readonly IEmployeeRepository _repo;
     public PayrollService(IEmployeeRepository repo)
     {
          _repo = repo;
     }

     public decimal GetNetSalary(int employeeId)
     {
          var emp = _repo.GetById(employeeId);
          var calc = new SalaryCalculator();
          return calc.CalculateNetSalary(emp);
     }
}