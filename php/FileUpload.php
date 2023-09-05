<?php

// Include necessary PHP files
include_once "Functions.php";
include_once "MySqlDb.php";

// Check if the request method is POST, return a 400 response if not.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(400);
    jsonResponse(['message' => "This script only accepts POST."], 400);
}

// Retrieve the uploaded CSV file, if any.
$csvFile = $_FILES['csvFile'] ?? null;

// Check if a file was not uploaded, return a 400 response if so.
if (!$csvFile) {
    jsonResponse(['message' => "No file uploaded."], 400);
}

// Check for errors during file upload, return a 400 response if an error occurred.
if ($csvFile["error"] !== 0) {
    jsonResponse(['message' => "Error uploading file."], 400);
}

// Get the name and temporary name of the uploaded file.
$fileName = $csvFile["name"] ?? null;
$tmpName = $csvFile["tmp_name"] ?? null;

// Extract the file extension and ensure it's a CSV file.
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
if ($fileExtension !== "csv") {
    jsonResponse(['message' => "Invalid file format. Please upload a CSV file."], 400);
}

// Create a new instance of the MySqlDb class.
$mySql = new MySqlDb();

// Open and read the CSV file.
$file = fopen($tmpName, "r");

// Return a 400 response if there was an error opening the file.
if ($file === false) {
    jsonResponse(['message' => "Error opening the file."], 400);
}

// Read the header row of the CSV file.
$header = fgetcsv($file);

// Ensure the CSV file has the expected columns.
$expectedColumns = [
    "Company Name",
    "Employee Name",
    "Email Address",
    "Salary"
];

// Compare the header with the expected columns, return a 400 response if they don't match.
if ($header !== $expectedColumns) {
    fclose($file);
    jsonResponse(['message' => "Invalid CSV format. Make sure the CSV file has the correct columns."], 400);
}

// Retrieve existing Employees from the database.
$statement = $mySql->prepareStatement("SELECT employeeId, email FROM Employee");
$mySql->bindParams($statement);
$mySql->executeStatement($statement);
$result = $mySql->getResult($statement);
$existingEmployees = $mySql->fetchAssoc($result, 'email');
$mySql->closeStatement($statement);
$existingEmployees = array_change_key_case($existingEmployees, CASE_LOWER);

// Retrieve existing Companies from the database.
$statement = $mySql->prepareStatement("SELECT companyId, `name` FROM Company");
$mySql->bindParams($statement);
$mySql->executeStatement($statement);
$result = $mySql->getResult($statement);
$existingCompanies = $mySql->fetchAssoc($result, 'name');
$mySql->closeStatement($statement);
$existingCompanies = array_change_key_case($existingCompanies, CASE_LOWER);

// Prepare insert statements for Employees, Companies, and Salary.
$insertEmployeeStatement = $mySql->prepareStatement("INSERT INTO Employee (`name`, email) VALUES (?, ?)");
$insertCompanyStatement = $mySql->prepareStatement("INSERT INTO Company (`name`) VALUES (?)");
$insertSalaryStatement = $mySql->prepareStatement("INSERT INTO EmployeeSalary (employeeId, companyId, salary) VALUES (?, ?, ?)");

// Loop through the CSV data and insert records into the database.
while (($row = fgetcsv($file)) !== false) {
    // Create objects to store data from CSV columns.
    $employee = new stdClass();
    $company = new stdClass();
    $salary = new stdClass();

    // Extract values from the CSV row.
    [$company->name, $employee->name, $employee->email, $salary->salary] = $row;

    // Check if the employee already exists based on email.
    $emailKey = strtolower($employee->email);
    $existingEmployee = $existingEmployees[$emailKey] ?? null;

    if ($existingEmployee) {
        $employee->employeeId = $existingEmployee->employeeId;
    } else {
        // Insert the employee if they don't exist.
        $mySql->bindParams($insertEmployeeStatement, $employee->name, $employee->email);
        $mySql->executeStatement($insertEmployeeStatement);
        $employee->employeeId = $insertEmployeeStatement->insert_id;
        $existingEmployees[$emailKey] = $employee;
    }

    // Check if the company already exists based on name.
    $companyKey = strtolower($company->name);
    $existingCompany = $existingCompanies[$companyKey] ?? null;

    if ($existingCompany) {
        $company->companyId = $existingCompany->companyId;
    } else {
        // Insert the company if it doesn't exist.
        $mySql->bindParams($insertCompanyStatement, $company->name);
        $mySql->executeStatement($insertCompanyStatement);
        $company->companyId = $insertCompanyStatement->insert_id;
        $existingCompanies[$companyKey] = $company;
    }

    // Insert the salary information.
    $mySql->bindParams($insertSalaryStatement, $employee->employeeId, $company->companyId, $salary->salary);
    $mySql->executeStatement($insertSalaryStatement);
}

// Close prepared statements, CSV file, and database connection.
$mySql->closeStatement($insertEmployeeStatement);
$mySql->closeStatement($insertCompanyStatement);
$mySql->closeStatement($insertSalaryStatement);

fclose($file);

$mySql->close();

// Delete the temporary uploaded CSV file.
unlink($tmpName);

// Return a success response.
jsonResponse(['message' => "Data inserted successfully!"]);
