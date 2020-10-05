<?php
define("ERRORS", "errors");

class ErrorObject {
    public $id;
    public $desc;

    public function __construct($id, $desc) {
        $this->id = $id;
        $this->desc = $desc;
    }
};

class ErrorHandler {
    public static function LogError(&$response, $error) {
        array_push($response[ERRORS], $error);
    }
};


?>