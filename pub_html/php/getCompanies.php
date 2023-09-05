<?php

include_once "Functions.php";
include_once "MySqlDb.php";

$mySql = new MySqlDb();

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

foreach ($companies as $company) {
    $company->averageSalary = $salaries[$company->companyId]->averageSalary ?? null;

    if ($company->averageSalary !== null) {
        $company->averageSalary = floatval($company->averageSalary);
    }
}

jsonResponse(['companies' => $companies]);