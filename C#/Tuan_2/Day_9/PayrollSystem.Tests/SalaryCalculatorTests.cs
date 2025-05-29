using PayrollSystem;
using Xunit;

namespace PayrollSystem.Tests;

public class SalaryCalculatorTests
{
     private readonly SalaryCalculator _calculator;

     public SalaryCalculatorTests()
     {
          _calculator = new SalaryCalculator();
     }

     [Fact]
     public void CalculateNetSalary_RegularEmployee_ReturnsCorrectSalary()
     {
          // Arrange
          var employee = new Employee
          {
               Id = 1,
               Name = "Nguyen Van A",
               BaseSalary = 1000m,
               Bonus = 200m,
               Deduction = 100m
          };

          // Act
          var result = _calculator.CalculateNetSalary(employee);

          // Assert
          Assert.Equal(1100m, result);
     }

     [Fact]
     public void CalculateNetSalary_NoBonusNoDeduction_ReturnsBaseSalary()
     {
          // Arrange
          var employee = new Employee
          {
               Id = 2,
               Name = "Tran Thi B",
               BaseSalary = 1000m,
               Bonus = 0m,
               Deduction = 0m
          };

          // Act
          var result = _calculator.CalculateNetSalary(employee);

          // Assert
          Assert.Equal(1000m, result);
     }

     [Fact]
     public void CalculateNetSalary_NegativeBaseSalary_ThrowsArgumentException()
     {
          // Arrange
          var employee = new Employee
          {
               Id = 3,
               Name = "Le Van C",
               BaseSalary = -1000m,
               Bonus = 200m,
               Deduction = 100m
          };

          // Act & Assert
          Assert.Throws<ArgumentException>(() => _calculator.CalculateNetSalary(employee));
     }

     [Fact]
     public void CalculateNetSalary_NegativeBonus_ThrowsArgumentException()
     {
          // Arrange
          var employee = new Employee
          {
               Id = 4,
               Name = "Pham Van D",
               BaseSalary = 1000m,
               Bonus = -200m,
               Deduction = 100m
          };

          // Act & Assert
          Assert.Throws<ArgumentException>(() => _calculator.CalculateNetSalary(employee));
     }

     [Fact]
     public void CalculateNetSalary_NullEmployee_ThrowsArgumentNullException()
     {
          // Act & Assert
          Assert.Throws<ArgumentNullException>(() => _calculator.CalculateNetSalary(null));
     }
}