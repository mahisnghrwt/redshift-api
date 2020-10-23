<?php

if (!file_exists(OUTPUT_PATH . $id . ".png")) {
    echo json_encode("Graph not found!");
    http_response_code(404);
    exit();
}

header('Content-type: image/png');
$image = file_get_contents(OUTPUT_PATH . $id . ".png");
echo $image;

?>