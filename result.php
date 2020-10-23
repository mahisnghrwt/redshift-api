<?php
require_once('globals.php');
require_once('error-handler.php');
require_once('database.php');
require_once('authenticator.php');
require_once('utility.php');

$json = json_decode(file_get_contents("php://input"));
$response = array();
$response['errors'] = array();
$response['result'] = array();
$database = new Database_mysqli();
$authenticator = new Authenticator($database);
$email = "";

# Cannot parse JSON
if ($json == NULL)
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Couldn't parse JSON string."));

#If calculation_ids is missing
if (!isset($json->calculation_ids))
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Missing array<int> calculation_ids."));

#if calculation_ids is not an array
if (!is_array($json->calculation_ids))
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Expected array<int> calculation_ids."));

$length = count($json->calculation_ids);

#calclation_ids must have atleast one element
if ($length < 1)
	Utility::Die(400, $response, new ErrorObject("Bad JSON", "Must contain atleast one calculation_id to check status for!"));

#Authentication token is missing    
if (!isset($json->metadata->token))
	Utility::Die(401, $response, new ErrorObject("Unauthorized", "Authentication Token is missing!"));

#Authenticate
if (!$authenticator->Check($json->metadata->token, $email))
	Utility::Die(401, $response, new ErrorObject("Unauthorized", "Invalid token!"));

#sort the calculation_ids as 'good' or 'bad'
$goodCalculationIDs = array();
$badCalculationIDs = array();
for ($i = 0; $i < $length; $i++) {
    $x = $json->calculation_ids[$i];
    if (is_numeric($x))
        array_push($goodCalculationIDs, $x);
    else if (is_string($x))
        array_push($badCalculationIDs, $x);
}

# Get the authorization level of the user
$auhtorizationLevel = $database->GetAuthorizationLevel($email);
$isAdmin = true ? $auhtorizationLevel === 1: false;

$result_s = $database->SelectResult($isAdmin === TRUE ? null : $email);
# if the calculation_id exists in the $result array returned from the database, then push it to the response array, otherwise, log error.
foreach ($goodCalculationIDs as $x) {
    if (array_key_exists($x, $result_s)) {
        $response['result'][$x] = $result_s[$x];
    }
    else
        array_push($response['errors'], new ErrorObject("calculation_id not found", "Could not fetch result for calculation_id '{$x}', either result does not exist or you are unauthorized.", NULL));
}

# for all the non-numeric calculation_ids, log error.
foreach ($badCalculationIDs as $x) {
    array_push($response['errors'], new ErrorObject("Invalid calculation_id", "Expected calculation_id to be a number, '{$x}' is not a number.", NULL));
}

#print the response back
echo json_encode($response, JSON_PRETTY_PRINT);
?>