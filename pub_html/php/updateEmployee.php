<?php

include_once "Functions.php";
include_once "MySqlDb.php";

if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
    jsonResponse(["message" => "This script only accepts PUT."], 400);
}

$contentType = trim($_SERVER["CONTENT_TYPE"] ?? "");
if ($contentType !== "application/json") {
    jsonResponse(["message" => "This script only accepts JSON."], 415);
}

$body = file_get_contents("php://input");

if (!$body) {
    jsonResponse(["message" => "No body data in request."], 400);
}

// Attempt to decode the JSON data
$payload = json_decode($body);

if (!is_object($payload)) {
    jsonResponse(["message" => "No JSON object in request."], 400);
}

if (!$payload->employeeId) {
    jsonResponse(["message" => "Employee ID was not passed and is required."], 400);
}

if (!$payload->email) {
    jsonResponse(["message" => "Email was not passed and is required."], 400);
}

if (!$payload->name) {
    jsonResponse(["message" => "Name was not passed and is required."], 400);
}

$mySql = new MySqlDb();

$statement = $mySql->prepareStatement("UPDATE
    Employee
    SET `name` = ?, email = ?
    WHERE employeeId = ?
");

$mySql->bindParams($statement, $payload->name, $payload->email, $payload->employeeId);

$mySql->executeStatement($statement);

jsonResponse(["message" => "Employee updated."]);