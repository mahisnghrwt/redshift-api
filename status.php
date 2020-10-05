<?php
require_once("Database.php");
require_once("Utility.php");
require_once("Authenticator.php");
require_once("error-handler.php");

define("HEADER_400", "HTTP/1.0 400 Bad Request");
define("HEADER_401", "HTTP/1.0 401 Unauthorized");

$database = new Database_mysqli();
$authenticator = new Authenticator($database);

$json = json_decode(file_get_contents("php://input"));

if ($json == NULL)
    Utility::Die(HEADER_400, $response, new ErrorObject("Bad JSON", "Couldn't parse JSON string."));

$length = count($json->calculation_ids);

$response = array();
$response["errors"] = array();

if ($length < count($json))
	Utility::Die(HEADER_400, $response, new ErrorObject("Bad JSON", "Partial JSON."));
	
if ($length > 1)
	if (!$authenticator->Check(Utility::ExtractToken(isset($json->metaData) ? $json->metaData : NULL)))
		Utility::Die(HEADER_401, $response, new ErrorObject("Unauthorized", "Invalid token."));
		
Utility::Uniformize($json->calculation_ids, $length, '/^[0-9]*$/', "int");

//TODO: check if theere are any valid ids still left

$tableName = "redshifts";
$columns = array("status", "calculation_id");
$key = "calculation_id";
$where = array("calculation_id" => $json->calculation_ids);

$response["result"] = $database->Select($tableName, $columns, $where);

if ($response["result"] == NULL) {
	echo "Something went wrong!";
	exit();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>