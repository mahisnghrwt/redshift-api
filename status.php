<?php
require_once('globals.php');
require_once('database.php');
require_once('publisher.php');
require_once('authenticator.php');
require_once('utility.php');
require_once('error-handler.php');

$database = new Database_mysqli();
$authenticator = new Authenticator($database);

$json = json_decode(file_get_contents("php://input"));
$response = array();
$response["status"] = array();
$response["errors"] = array();

if ($json == NULL)
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Couldn't parse JSON string."));

if (!isset($json->calculation_ids))
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Missing array<int> calculation_ids."));

if (!is_array($json->calculation_ids))
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Expected array<int> calculation_ids."));

    $length = count($json->calculation_ids);
    $email = "";

if ($length < 1)
	Utility::Die(400, $response, new ErrorObject("Bad JSON", "Must contain atleast one calculation_id to check status for!"));

if (!isset($json->metadata->token))
	Utility::Die(401, $response, new ErrorObject("Unauthorized", "Token not found!"));

if (!$authenticator->Check($json->metadata->token, $email))
	Utility::Die(401, $response, new ErrorObject("Unauthorized", "Invalid token!"));
		
$goodCalculationIDs = array();
$badCalculationIDs = array();
for ($i = 0; $i < $length; $i++) {
    $x = $json->calculation_ids[$i];
    if (is_numeric($x))
        array_push($goodCalculationIDs, $x);
    else if (is_string($x))
        array_push($badCalculationIDs, $x);
}
$json->calculation_ids = $goodCalculationIDs;

$auhtorizationLevel = $database->GetAuthorizationLevel($email);
$isAdmin = true ? $auhtorizationLevel === 1: false;

# get the list of all calculation_id => status, for the user
$status_s = $database->SelectAllStatus($isAdmin === TRUE ? NULL: $email);

# iterate over goodCalculationID, copy the ones requested by the user
foreach ($goodCalculationIDs as $x) {
    if (array_key_exists($x, $status_s))
        $response["status"][$x] = $status_s[$x];
    else
        array_push($response['errors'], new ErrorObject("calculation_id not found", "Could not fetch status for calculation_id '{$x}', either status does not exist or you are unauthorized.", NULL));
}

foreach ($badCalculationIDs as $x) {
    array_push($response['errors'], new ErrorObject("Invalid calculation_id", "Expected calculation_id to be a number, '{$x}' is not a number.", NULL));
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>