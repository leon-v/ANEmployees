<?php

// Include necessary PHP files
include_once "Functions.php";
include_once "MySqlDb.php";

// Initialize the MySQL database connection
$mySql = new MySqlDb();

// Prepare and execute a SQL statement to retrieve employee information
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

// Get the result of the SQL query
$result = $mySql->getResult($getEmployeesStatement);

// Fetch the employee data as an associative array
$employeeData = $mySql->fetchAssoc($result);

// Send a JSON response with the list of employees and their details
jsonResponse(['employees' => $employeeData]);