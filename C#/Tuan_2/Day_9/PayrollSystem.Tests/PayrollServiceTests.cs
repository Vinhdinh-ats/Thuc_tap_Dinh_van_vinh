using Moq;
using PayrollSystem;
using Xunit;

namespace PayrollSystem.Tests;

public class PayrollServiceTests
{
     private readonly Mock<IEmployeeRepository> _mockRepo;
     private readonly PayrollService _service;

     public PayrollServiceTests()
     {
          _mockRepo = new Mock<IEmployeeRepository>();
          _service = new PayrollService(_mockRepo.Object);
     }

     [Fact]
     public void GetNetSalary_ValidEmployeeId_ReturnsCorrectSalary()
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
          _mockRepo.Setup(repo => repo.GetById(1)).Returns(employee);

          // Act
          var result = _service.GetNetSalary(1);

          // Assert
          Assert.Equal(1100m, result);
     }

     [Fact]
     public void GetNetSalary_RepositoryReturnsNull_ThrowsArgumentNullException()
     {
          // Arrange
          _mockRepo.Setup(repo => repo.GetById(2)).Returns((Employee)null);

          // Act & Assert
          Assert.Throws<ArgumentNullException>(() => _service.GetNetSalary(2));
     }
}