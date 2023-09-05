<?php

// Include necessary PHP files
include_once "Functions.php";  // Include custom functions
include_once "MySqlDb.php";    // Include MySQL database utility class

// Check if the request method is PUT
if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
    jsonResponse(["error" => "This script only accepts PUT requests."], 405);
}

// Check the content type of the request
$contentType = trim($_SERVER["CONTENT_TYPE"] ?? "");
if ($contentType !== "application/json") {
    jsonResponse(["error" => "This script only accepts JSON requests."], 415);
}

// Get the request body data
$body = file_get_contents("php://input");

// Check if the request body is empty
if (!$body) {
    jsonResponse(["error" => "No request body data found."], 400);
}

// Attempt to decode the JSON data
$payload = json_decode($body);

// Check if the decoded payload is an object
if (!is_object($payload)) {
    jsonResponse(["error" => "Invalid JSON format in request body."], 400);
}

// Check for required fields in the JSON payload
if (!$payload->employeeId) {
    jsonResponse(["error" => "Employee ID is required."], 400);
}

if (!$payload->email) {
    jsonResponse(["error" => "Email is required."], 400);
}

if (!$payload->name) {
    jsonResponse(["error" => "Name is required."], 400);
}

// Create a new instance of the MySqlDb class
$mySql = new MySqlDb();

// Prepare and execute a SQL statement to update employee information
$statement = $mySql->prepareStatement("UPDATE
    Employee
    SET `name` = ?, email = ?
    WHERE employeeId = ?
");

// Bind parameters to the prepared statement
$mySql->bindParams($statement, $payload->name, $payload->email, $payload->employeeId);

// Execute the SQL statement
$mySql->executeStatement($statement);

// Send a JSON response to indicate successful employee update
jsonResponse(["message" => "Employee information updated successfully."], 200);
