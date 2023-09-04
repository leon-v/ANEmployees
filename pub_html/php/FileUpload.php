<?php

include_once "Functions.php";
include_once "MySqlDb.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(400);
    die("This script only accepts POST.");
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

// Initialize the MySQL database connection
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

// Prepare the insert statement once and get the statement
$insertStmt = $mySql->prepareStatement("INSERT
    INTO  Employee
    (company, `name`, email, salary)
    VALUES
    (?, ?, ?, ?)
");


// Loop through the CSV data and insert multiple rows
while (($row = fgetcsv($file)) !== false) {
    $companyName = $row[0];
    $employeeName = $row[1];
    $emailAddress = $row[2];
    $salary = $row[3];

    // Bind parameters using the statement
    $mySql->bindParams($insertStmt, array($companyName, $employeeName, $emailAddress, $salary));

    // Execute the statement
    $mySql->executeStatement($insertStmt);
}

// Close the prepared statement, CSV file, and database connection
$mySql->closeStatement($insertStmt);

fclose($file);

$mySql->close();

unlink($tmpName);

jsonResponse(['message' => "Data inserted successfully!"]);