<?php
require_once("Database.php");

$database = new Database_mysqli();

$response = array();

$tableName = "redshifts";
$columns = array("status", "calculation_id");
$where = array("calculation_id" => array($calculation_id));

$response["result"] = $database->Select($tableName, $columns, $where, NULL);

if ($response["result"] == NULL) {
	echo "Something went wrong!";
	exit();
}

echo json_encode($response, JSON_PRETTY_PRINT);
//echo json_encode($response["result"][0], JSON_PRETTY_PRINT);
?>