<?php
//Necessary header files
require_once('globals.php');
require_once('database.php');
require_once('publisher.php');
require_once('authenticator.php');
require_once('utility.php');
require_once('error-handler.php');
require_once('calculator.php');

$publisher = new Publisher();   //To publish jobs to a worker process
$database = new Database_mysqli();  //Communicate with Database
$authenticator = new Authenticator($database);  //For Token authentication
$response["errors"] = array();
$response["result"] = NULL;
//Get the POST data
$json = json_decode(file_get_contents("php://input"));

//If invalid JSON
if ($json == NULL)
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Couldn't parse JSON."));

if (!isset($json->data) || !isset($json->methods))
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Must contain data and methods!"));

//Get the list of all methods available from the database
$methodList = $database->GetMethods();
$l = count($json->methods);

//Check if the requested method is avaialable, if it isn't then remove it from the 'metadata['methods']' and log an error in response as well
$available_methods = array();
for ($i = 0; $i < $l; $i++) {
    if (is_numeric($json->methods[$i]) && array_key_exists($json->methods[$i], $methodList)) {
        array_push($available_methods, $json->methods[$i]);
    }
    else {
        ErrorHandler::LogError($response, new ErrorObject("Invalid Method", "Method with id {$json->methods[$i]} not found!"));
    }
}

$json->methods = $available_methods;

//If none of the requested methods are available, then Die
if (count($json->methods) <= 0) {
    Utility::Die(400, $response);
}

//Validate the arguments
$errors = array();
$args = Utility::GetArgs($json->data, $errors);
//If NULL, means atleast one the argument is invalid
if ($args == NULL) {
    ErrorHandler::LogError($response, new ErrorObject("Invalid Args", "Invalid arguments in the request.", $errors));
    Utility::Die(400, $response);
}

//This array would contain all the requested calculations, its each element represents a batch
$master_job_s = array();

//For all requested methods
$i = 0;
foreach ($json->methods as $m) {
    array_push($master_job_s, array("method_id" => $m, "script_path" => $methodList[$m], "args" => $args, "unique_id" => uniqid(), "galaxy_id" => uniqid("guest")));//For all requested calculations
    $i++;
}
$response['result'] = array();
$l = count($master_job_s);
for ($i = 0; $i < $l; $i++) {
    $fprefix = time();
    $arg = array($master_job_s[$i]);
    $result = Calculator::PerformCalc($master_job_s[$i]['script_path'], $arg);
    if (is_null($result))
        continue;
    else {
        $mid = $master_job_s[$i]['method_id'];
        $rep = array();
        $rep["redshift_result"] = !is_null($result["redshift_result"]) ? $result["redshift_result"][0]: NULL;
        $rep["redshift_alt_result"] = !is_null($result["redshift_alt_result"]) ? $result["redshift_alt_result"][0]: NULL;
        $response['result'][$mid] = $rep;
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>