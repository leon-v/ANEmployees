<?php

include_once "Functions.php";
include_once "MySqlDb.php";

// Initialize the MySQL database connection
$mySql = new MySqlDb();

// Assuming you have a MySQLi object named $mySql

// Prepare the statement
$getEmployeesStatement = $mySql->prepareStatement("SELECT
    Company.name AS company,
    Company.companyId,
    Employee.name,
    Employee.email,
    Employee.employeeId,
    EmployeeSalary.salary
    FROM Employee
    JOIN EmployeeSalary USING(employeeId)
    JOIN Company USING(companyId)
");

$mySql->executeStatement($getEmployeesStatement);

$result = $mySql->getResult($getEmployeesStatement);

$employeeData = $mySql->fetchAssoc($result);

jsonResponse(['employees' => $employeeData]);