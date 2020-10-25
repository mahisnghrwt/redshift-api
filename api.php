<?php
//Necessary header files
require_once('globals.php');
require_once('database.php');
require_once('publisher.php');
require_once('authenticator.php');
require_once('utility.php');
require_once('error-handler.php');

define("BATCH_SIZE", 100);

$metadata = null;   //Array that will store job_id, token, and methods
$methodList = null; //List of available methods
$batchCounter = -1;
$firstCalcID = -1;
$response = array();    //This will be echo'd back to the user
$response['calculation_ids'] = array();
$response['errors'] = array();

$publisher = new Publisher();   //To publish jobs to a worker process
$database = new Database_mysqli();  //Communicate with Database
$authenticator = new Authenticator($database);  //For Token authentication

//Get the POST data
$json = json_decode(file_get_contents("php://input"));

//If invalid JSON
if ($json == NULL)
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "Couldn't parse JSON."));

//Get the length of the array in JSON
$length = count($json);

//Lenth of array must be atleast two, since the last element contains metadata, hence it has atleast one element representing the requested calculation
if ($length < 2)
    Utility::Die(400, $response, new ErrorObject("Bad JSON", "JSON in unexpected format."));

//Guest can only perform one calculation per request
if ($isGuest && $length > 2)
    Utility::Die(401, $response, new ErrorObject("Unauthorized", "Guest can only perform one calculation per request."));

//If the user isn't Guest, the last element of the array must contain 'token' for authentication
if (!$isGuest) {
    if (!isset($json[$length - 1]->token))
        Utility::Die(401, $response, new ErrorObject("Unauthorized", "Authentication Token not found!"));
    if (!$authenticator->Check($json[$length - 1]->token))
        Utility::Die(401, $response, new ErrorObject("Unauthorized", "Invalid Authentication Token!"));
}

//Store the metadata in the 'metadata' array we declared earlier
$metadata = Utility::ExtractMetaData($json[$length - 1], $response, $isGuest);
//Die, if metadata isn't present
if ($metadata == NULL)
    Utility::Die(400, $response);

//Get the list of all methods available from the database
$methodList = $database->GetMethods();

//This array would contain all the requested calculations, its each element represents a batch
$master_job_s = array();

$t = count($metadata[METHODS]);
//Check if the requested method is avaialable, if it isn't then remove it from the 'metadata['methods']' and log an error in response as well
for ($i = 0; $i < $t; $i++) {
    if (!array_key_exists($metadata[METHODS][$i], $methodList)) {
        ErrorHandler::LogError($response, new ErrorObject("Invalid Method", "Method with id {$metadata[METHODS][$i]} not found!"));
        unset($metadata[METHODS][$i]);
    }
}

//If none of the requested methods are available, then Die
if (count($metadata[METHODS]) <= 0) {
    Utility::Die(400, $response);
}

//For all requested methods
for ($j = 0; $j < count($metadata[METHODS]); $j++) {
    //For all requested calculations
    for ($i = 0; $i < $length - 1; $i++) {
        //Validate the arguments
        $errors = array();
        $args = Utility::GetArgs($json[$i], $errors);
        //If NULL, means atleast one the argument is invalid
        if ($args == NULL) {
            ErrorHandler::LogError($response, new ErrorObject("Invalid Args", "Invalid arguments at index {$i}.", $errors));
            Utility::Die(400, $response);
        }

        //Update the batch_counter
        if ($i % BATCH_SIZE == 0) {
            $batchCounter++;
            $master_job_s[$batchCounter] = array();
        }
        //Push the calculation request to the current batch
        array_push($master_job_s[$batchCounter], array("method_id" => $metadata[METHODS][$j], "script_path" => $methodList[$metadata[METHODS][$j]], "args" => $args, "unique_id" => uniqid()));
    }
}

//If for some reason, there are zero batches, then Die again
if (count($master_job_s) == 0)
    Utility::Die(400, $response);

//Insert all the requested calculations into the database, and set their 'status' as 'SUBMITTED'
$firstCalcID = $database->InsertIntoRedshift($json, $metadata["job_id"], "SUBMITTED", $length - 1);
if ($firstCalcID === -1) {
    ErrorHandler::LogError($response, new ErrorObject("Unkown error occured", "Error occured while inserting data into database.", NULL));
    Utility::Die(400, $response);
}

$calcID_s = array(); //This store calculation ids for calculations with valid arguments
$realID = 0;
$idCounter = 0;

$lastMethodID = $master_job_s[0][0]['method_id'];
//Add a new property 'galaxy_id' to the requested calculations
for ($i = 0; $i < count($master_job_s); $i++) {
    if ($master_job_s[$i][0]["method_id"] != $lastMethodID)
        $idCounter = 0;
    for ($j = 0; $j < count($master_job_s[(string)$i]); $j++) {
        //Add the initial calculationID with the counter, to get the current calculationID
        $realID = $firstCalcID + $idCounter;
        $idCounter++;
        $master_job_s[$i][$j]["galaxy_id"]= $realID;
        //Push the calculation_id0 to the calcID_s array, later we will send this along with the response to the user
        if (!in_array($realID, $calcID_s))
            array_push($calcID_s, $realID);
    }
}

#Set the status as 'SUBMITTED'
foreach($master_job_s as $x) {
    $database->InsertIntoStatus($x, "SUBMITTED");
}

//Now publish the calculations in batches to the 'worker' processes
for ($i = 0; $i < count($master_job_s); $i++) {
    $publisher->Send($master_job_s[$i]);
}

//Send the response back to the user
$response['calculation_ids'] = $calcID_s;
echo json_encode($response);
?>