<?php
require_once('globals.php');
define("ERRORS", "errors");

class ErrorObject {
    public $id;
    public $desc;
    public $errors;

    public function __construct($id, $desc, $errors = NULL) {
        $this->id = $id;
        $this->desc = $desc;
        $this->errors = $errors;
    }
};

class ErrorHandler {
    public static function LogError(&$response, $error) {
        array_push($response[ERRORS], $error);
    }
};


?>