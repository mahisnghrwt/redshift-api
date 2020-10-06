<?php
require_once("Database.php");

$database = new Database_mysqli();

$response = array();
$response["status"] = "";
$response["errors"] = array();

$response["status"] = $database->SelectSingleStatus($calculation_id);

echo json_encode($response, JSON_PRETTY_PRINT);
?>