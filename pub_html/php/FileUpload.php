<?php

include_once "Functions.php";
include_once "MySqlDb.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(400);
    jsonResponse(['message' => "This script only accepts POST."], 400);
}

$csvFile = $_FILES['csvFile'] ?? null;

if (!$csvFile) {
    jsonResponse(['message' => "No file uploaded."], 400);
}

if ($csvFile["error"] !== 0) {
    jsonResponse(['message' => "Error uploading file."], 400);
}

$fileName = $csvFile["name"] ?? null;
$tmpName = $csvFile["tmp_name"] ?? null;

// Validate file extension (CSV)
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
if ($fileExtension !== "csv") {
    jsonResponse(['message' => "Invalid file format. Please upload a CSV file."], 400);
}

$mySql = new MySqlDb();

// Open and read the CSV file
$file = fopen($tmpName, "r");
if ($file === false) {
    jsonResponse(['message' => "Error opening the file."], 400);
}

// Read the header row
$header = fgetcsv($file);

// Ensure the CSV file has the expected columns
$expectedColumns = [
    "Company Name",
    "Employee Name",
    "Email Address",
    "Salary"
];

if ($header !== $expectedColumns) {
    fclose($file);
    jsonResponse(['message' => "Invalid CSV format. Make sure the CSV file has the correct columns."], 400);
}


// Get existing Employees
$statement = $mySql->prepareStatement("SELECT
    employeeId, email
    FROM Employee
");
$mySql->bindParams($statement);
$mySql->executeStatement($statement);
$result = $mySql->getResult($statement);
$existingEmployees = $mySql->fetchAssoc($result, 'email');
$mySql->closeStatement($statement);
$existingEmployees = array_change_key_case($existingEmployees, CASE_LOWER);


// Get existing Companies
$statement = $mySql->prepareStatement("SELECT
    companyId, `name`
    FROM Company
");
$mySql->bindParams($statement);
$mySql->executeStatement($statement);
$result = $mySql->getResult($statement);
$existingCompanies = $mySql->fetchAssoc($result, 'name');
$mySql->closeStatement($statement);
$existingCompanies = array_change_key_case($existingCompanies, CASE_LOWER);


// Prepare the insert statement once and get the statement
$insertEmployeeStatement = $mySql->prepareStatement("INSERT
    INTO Employee
    (`name`, email)
    VALUES
    (?, ?)
");

$insertCompanyStatement = $mySql->prepareStatement("INSERT
    INTO Company
    (`name`)
    VALUES
    (?)
");

$insertSallaryStatement = $mySql->prepareStatement("INSERT
    INTO EmployeeSalary
    (employeeId, companyId, salary)
    VALUES
    (?, ?, ?)
");


// Loop through the CSV data and insert multiple rows
while (($row = fgetcsv($file)) !== false) {

    $employee = new stdClass();
    $company = new stdClass();
    $salary = new stdClass();

    [$company->name, $employee->name, $employee->email, $salary->salary] = $row;

    // Add the employee if they don't exist already.
    $emailKey = strtolower($employee->email);
    $existingEmployee = $existingEmployees[$emailKey] ?? null;

    if ($existingEmployee) {
        $employee->employeeId = $existingEmployee->employeeId;
    }
    else{
        $mySql->bindParams($insertEmployeeStatement, $employee->name, $employee->email);
        $mySql->executeStatement($insertEmployeeStatement);
        $employee->employeeId = $insertEmployeeStatement->insert_id;
        $existingEmployees[$emailKey] = $employee;
    }

    // Add the company if they don't exist already
    $companyKey = strtolower($company->name);
    $existingCompany = $existingCompanies[$companyKey] ?? null;

    if ($existingCompany) {
        $company->companyId = $existingCompany->companyId;
    }
    else{
        $mySql->bindParams($insertCompanyStatement, $company->name);
        $mySql->executeStatement($insertCompanyStatement);
        $company->companyId = $insertCompanyStatement->insert_id;
        $existingCompanies[$companyKey] = $company;
    }

    $mySql->bindParams($insertSallaryStatement, $employee->employeeId, $company->companyId, $salary->salary);
    $mySql->executeStatement($insertSallaryStatement);
}

// Close the prepared statement, CSV file, and database connection
$mySql->closeStatement($insertEmployeeStatement);
$mySql->closeStatement($insertCompanyStatement);
$mySql->closeStatement($insertSallaryStatement);

fclose($file);

$mySql->close();

unlink($tmpName);

jsonResponse(['message' => "Data inserted successfully!"]);