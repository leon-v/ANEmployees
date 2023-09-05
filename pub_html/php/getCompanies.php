<?php

// Include necessary PHP files
include_once "Functions.php";
include_once "MySqlDb.php";

// Create a new instance of the MySqlDb class
$mySql = new MySqlDb();

// Prepare and execute a SQL statement to calculate the average salary for each company
// Aggregate query uses index for all data. Will perform well.
$statement = $mySql->prepareStatement("SELECT
        EmployeeSalary.companyId,
        AVG(EmployeeSalary.salary) AS averageSalary
    FROM EmployeeSalary
    GROUP BY(EmployeeSalary.companyId)
");
$mySql->executeStatement($statement);
$result = $mySql->getResult($statement);
$salaries = $mySql->fetchAssoc($result, 'companyId');
$mySql->closeStatement($statement);

// Prepare and execute a SQL statement to retrieve company information ordered by name
$statement = $mySql->prepareStatement("SELECT
    companyId,
    name
    FROM Company
    ORDER BY name
");
$mySql->executeStatement($statement);
$result = $mySql->getResult($statement);
$companies = $mySql->fetchAssoc($result);
$mySql->closeStatement($statement);

// Calculate and assign the average salary to each company
foreach ($companies as $company) {
    $company->averageSalary = $salaries[$company->companyId]->averageSalary ?? null;

    // Convert average salary to a float if it's not null
    if ($company->averageSalary !== null) {
        $company->averageSalary = floatval($company->averageSalary);
    }
}

// Send a JSON response with the list of companies and their average salaries
jsonResponse(['companies' => $companies]);