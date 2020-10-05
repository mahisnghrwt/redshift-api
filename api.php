<?php
//Necessary header files
require_once('database.php');
require_once('publisher.php');
require_once('authenticator.php');
require_once('utility.php');
require_once('error-handler.php');

define("batch_size", 100);
define("CALC_IDS", "calculation_ids");

define("HEADER_400", "HTTP/1.0 400 Bad Request");
define("HEADER_401", "HTTP/1.0 401 Unauthorized");
define("REDSHIFT_COLS", array("optical_u", "optical_v", "optical_g", "optical_r", "optical_i", "optical_z", "infrared_three_six", "infrared_four_five", "infrared_five_eight", "infrared_eight_zero", "infrared_J", "infrared_H", "infrared_K", "radio_one_four", "status", "job_id"));


//kinda Global Variables
$metaData = null;
$methodList = null;
$batchCounter = -1;
$firstCalcID = -1;
$response = array();
$response[CALC_IDS] = array();
$response[ERRORS] = array();

//All the objects that we will use later on
$publisher = new Publisher();
$database = new Database_mysqli();
$authenticator = new Authenticator($database);

//Get user input from post request
$json = json_decode(file_get_contents("php://input"));

//If JSON is invalid
if ($json == NULL)
    Utility::Die(HEADER_400, $response, new ErrorObject("Bad JSON", "Couldn't parse JSON string."));

$length = count($json);

if ($length < 2)
    Utility::Die(HEADER_400, $response, new ErrorObject("Bad JSON", "Partial JSON."));

if ($isGuest && $length > 2)
    Utility::Die(HEADER_401, $response, new ErrorObject("Unauthorized", "Guest can only perform once calculation per request."));

if (!$isGuest)
    if (!$authenticator->Check(Utility::ExtractToken($json)))
        Utility::Die(HEADER_401, $response, new ErrorObject("Unauthorized", "Invalid token."));

$metaData = Utility::ExtractMetaData($json[$length - 1], $response, $isGuest);
if ($metaData == NULL)
    Utility::Die(HEADER_400, $response);
/*
Check whether all the necessary arguments are passed in json script
*/
$query_s = array();
$firstID = -1;
$methodList = $database->GetMethodList();

//Get list of all the methods
$master_job_s = array();

$t = count($metaData[METHODS]);
/* Check if the requested methods exists */
for ($i = 0; $i < $t; $i++) {
    if (!array_key_exists($metaData[METHODS][$i], $methodList)) {
        ErrorHandler::LogError($response, new ErrorObject("Invalid Method", "Method with id {$metaData[METHODS][$i]} not found!"));
        unset($metaData[METHODS][$i]);
    }
}
unset($t);

if (count($metaData[METHODS]) <= 0) {
    Utility::Die(HEADER_400, $response);
}

//All the requested methods
for ($j = 0; $j < count($metaData[METHODS]); $j++) {
    for ($i = 0; $i < $length - 1; $i++) {
        //array_push($query_s, $json[$i]);
        $args = Utility::GetArgs($json[$i], $response);
        if ($args == NULL) {
            ErrorHandler::LogError($response, new ErrorObject("Invalid Args", "Invalid arguments on index {$i}."));
            continue;
        }

        if ($i % batch_size == 0) {
            $batchCounter++;
            $master_job_s[$batchCounter] = array();
        }
        array_push($master_job_s[$batchCounter], array("galaxyID" => -1, "methodID" => $metaData[METHODS][$j], "scriptPath" => $methodList[$metaData[METHODS][$j]], "args" => $args));
    }
}

$defaults = array("status" => "SUBMITTED", "job_id" => $metaData["job_id"]);
$firstCalcID = $database->Insert("redshifts", REDSHIFT_COLS, $json, $length - 1, $defaults);

//Insert the requests into the database
//$firstCalcID = $database->insertRedshift($query_s, $metaData["job_id"]);
$calcID_s = array(); //Will store all calculationIDs in it
$realID = 0;
$idCounter = 0;



//Assign the acutal galaxyID, to the elements inside job_s array.
for ($i = 0; $i < count($master_job_s); $i++) {
    for ($j = 0; $j < count($master_job_s[(string)$i]); $j++) {
        //Add the initial calculationID with counter, to get the current calculationID
        $realID = $firstCalcID + $idCounter;
        $idCounter++;
        //Update the calculationID inside job_s array
        $master_job_s[$i][$j]["galaxyID"]= $realID;
        //Push the calculationID to the calcID_s array
        array_push($calcID_s, $realID);
    }
}

//Now publish the jobs to consumers
for ($i = 0; $i < count($master_job_s); $i++) {
    $publisher->Send($master_job_s[$i]);
}

//Echo the calculationID_s back to user
//Will help them in checking the status of requested calculations
$response[CALC_IDS] = $calcID_s;
echo json_encode($response);
?>