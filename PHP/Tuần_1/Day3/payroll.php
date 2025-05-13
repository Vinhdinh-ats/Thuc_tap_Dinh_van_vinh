<?php

$conn = new mysqli("localhost", "root", "", "affiliate_db");
mysqli_set_charset($conn, "utf8");

function getEmployeePayrollData(mysqli $conn): array
{
     $standard_days = 22;

     $sql = "SELECT e.id, e.name, e.base_salary, 
                    COUNT(t.id) AS work_days,
                    a.allowance, a.deduction
             FROM employees e
             LEFT JOIN timesheets t ON e.id = t.employee_id
             LEFT JOIN adjustments a ON e.id = a.employee_id
             GROUP BY e.id";

     $result = $conn->query($sql);

     $payroll = [];
     $total_salary = 0;

     while ($row = $result->fetch_assoc()) {
          $actual_salary = round(($row['base_salary'] / $standard_days) * $row['work_days']
               + $row['allowance'] - $row['deduction']);
          $row['actual_salary'] = $actual_salary;
          $payroll[] = $row;
          $total_salary += $actual_salary;
     }

     return [$payroll, $total_salary];
}
