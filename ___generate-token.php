<?php
require_once('database.php');
require_once('__config.php');
header("Content-Type: application/json;");

$database = new Database_mysqli();
$users = array();
$users = $database->GetEmailPassword();
$randomString = "sahdkjashdjk872198798";
$delimiter = ":";

if (is_null($users)) {
    echo "Some error occured";
    exit();
}

$l = count($users);
for ($i = 0; $i < $l; $i++) {
    $email = $users[$i]["email"];
    $password = $users[$i]["password"];
    $users[$i]["tokenString"] = $email . $delimiter . $password . $delimiter . $randomString;
    $users[$i]["token"] = openssl_encrypt($users[$i]["tokenString"], cipherMethod, key, $options = 0, iv);

}

echo json_encode($users, JSON_PRETTY_PRINT);

?>