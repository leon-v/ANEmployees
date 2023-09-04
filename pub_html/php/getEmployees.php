<?php

include_once "Functions.php";
include_once "MySqlDb.php";

// Initialize the MySQL database connection
$mySql = new MySqlDb();

// Assuming you have a MySQLi object named $mySql

// Prepare the statement
$getEmployeesStatement = $mySql->prepareStatement("SELECT
    company,
    `name`,
    email,
    salary
    FROM Employee
");

// Execute the statement
$getEmployeesStatement->execute();

// Bind variables to store the results
$getEmployeesStatement->bind_result($company, $name, $email, $salary);

// Fetch data rows
$employeeData = [];

while ($getEmployeesStatement->fetch()) {
    // Create an associative array for each row
    $row = [
        'company' => $company,
        'name' => $name,
        'email' => $email,
        'salary' => $salary
    ];

    // Add the row to the result array
    $employeeData[] = $row;
}

jsonResponse(['employees' => $employeeData]);