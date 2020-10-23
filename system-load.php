<?php
require_once('globals.php');
require_once('error-handler.php');
require_once('database.php');
require_once('authenticator.php');
require_once('utility.php');

$json = json_decode(file_get_contents("php://input"));
$response = array();
$response['errors'] = array();
$database = new Database_mysqli();
$authenticator = new Authenticator($database);

if ($json == NULL)
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Couldn't parse JSON string."));

if (!isset($json->token))
    Utility::Die(401, $response, new ErrorObject("Unauthorized", "Token not found!"));

$email = "";
if (!$authenticator->Check($json->token, $email))
    Utility::Die(401, $response, new ErrorObject("Unauthorized", "Invalid token!"));

if ($database->GetAuthorizationLevel($email) != 1)
    Utility::Die(401, $response, new ErrorObject("Unauthorized", "Only Admins allowed!"));

$load = sys_getloadavg();
if ($load == NULL) {
    Utility::Die(400, $response, new ErrorObject("sys_getloadavg error", "Null value returned on sys_getloadavg() call!"));
}

$response['system-load'] = $load;

echo json_encode($response);
?>