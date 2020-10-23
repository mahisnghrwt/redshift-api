<?php

if (!file_exists(ERROR_PATH . $id . ".txt")) {
    echo json_encode("Error log not found!");
    http_response_code(404);
    exit();
}

header('Content-type: text/plain');
$errorLog = file_get_contents(ERROR_PATH . $id . ".txt");
echo $errorLog;

?>