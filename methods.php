<?php
require_once("database.php");

$database = new Database_mysqli();
if ($database == NULL)
	http_response_code(500);

$methods = $database->GetMethodList();
if ($methods == NULL) {
	echo "No methods found!";
	exit();
}
echo json_encode($methods, JSON_PRETTY_PRINT);
?>