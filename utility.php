<?php
define("METHODS", "methods");
define("TOKEN", "token");
define("JOB_ID", "job_ID");

require_once('error-handler.php');

class Utility {
    public static function GetArgs($arr, &$response) {
        $fields = array("optical_u" => "float", "optical_v" => "float", "optical_g" => "float", "optical_r" => "float", "optical_i" => "float", "optical_z" => "float",
        "infrared_three_six" => "float", "infrared_four_five" => "float", "infrared_five_eight" => "float", "infrared_eight_zero" => "float",
        "infrared_J" => "float", "infrared_H" => "float", "infrared_K" => "float", "radio_one_four" => "float");

        $result = array();

        $valid_arg = true;
        $is_valid = false;
        foreach($fields as $key => $value) {
            $is_valid = true;
            if (!isset($arr->{$key})) {
                ErrorHandler::LogError($response, new ErrorObject("Invalid argument", "{$key} not found!"));
                $valid_arg = false;
                continue;
            }
            else {
                switch($value) {
                    case "float":
                        $is_valid = is_float($arr->{$key}) ? true : false;
                        break; 
                    case "int":
                        $is_valid = is_int($arr->{$key}) ? true : false;
                        break; 
                    case "string":
                        $is_valid = is_string($arr->{$key}) ? true : false;
                        break; 
                    default:
                        $is_valid = false;
                }

                if (!$is_valid) {
                    $valid_arg = false;
                    ErrorHandler::LogError($response, new ErrorObject("Invalid argument", "Expected {$arr->$key} to be {$arr->value}."));
                }
                else
                    $result[$key] = $arr->{$key};
            }
        }

        return $valid_arg ? $result : NULL;
    }

    public static function ExtractToken($json) {
        if ($json == NULL)
            return NULL;
        $length = count($json);
        if ($length < 2)
            return NULL;

        if (!array_key_exists("token", $json[$length - 1]))
            return NULL;

        return $json[$length - 1]->token;
    }

    public static function ExtractMetaData($metadata_, &$response, $isGuest = false) {
        $metadata = array();
        $required = array("job_id", "methods", "token");
        $isBroken = false;

        foreach ($required as $prop) {
            if (isset($metadata_->{$prop}))
                $metadata[$prop] = $metadata_->{$prop};
            else {
                if ($isGuest &&  $prop == "token")
                    continue;
                $isBroken = true;
                ErrorHandler::LogError($response, new ErrorObject("Invalid metadata", "{$prop} not found!"));
            }
        }

        return $isBroken ? null: $metadata;
    }

    public static function Die($httpHeader, $response, $error = NULL) {
        if ($error != NULL)
            array_push($response[ERRORS], $error);

        header($httpHeader);
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit();
    }

    public static function Uniformize(&$data, $length, $regex, $castTo) {
        for ($i = 0; $i < $length; $i++) {
            if (preg_match($regex, $data[$i]) == 0) {
                unset($i);
            }
            else {
                switch ($castTo) {
                    case "int":
                        $data[$i] = (int)$data[$i];
                }
            }
        }
    }
};
?>