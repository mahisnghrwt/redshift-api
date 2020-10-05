<?php
require_once("Database.php");

$database = new Database_mysqli();

$tableName = "methods";
$columns = array("method_id", "method_name");

$methods = $database->Select($tableName, $columns);

if ($methods == NULL) {
	echo "Something went wrong!";
	exit();
}

echo json_encode($methods, JSON_PRETTY_PRINT);
?>