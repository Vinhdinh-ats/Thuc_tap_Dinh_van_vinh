namespace PayrollSystem;

public class Employee
{
     public int Id { get; set; }
     public string Name { get; set; } = "";
     public decimal BaseSalary { get; set; }
     public decimal Bonus { get; set; }
     public decimal Deduction { get; set; }
}