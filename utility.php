<?php
define("METHODS", "methods");
define("TOKEN", "token");
define("JOB_ID", "job_ID");

require_once('error-handler.php');

class Utility {
    public static function GetArgs($arr, &$errors) {
        $fields = array("optical_u" => "numeric", "optical_v" => "numeric", "optical_g" => "numeric", "optical_r" => "numeric", "optical_i" => "numeric", "optical_z" => "numeric",
        "infrared_three_six" => "numeric", "infrared_four_five" => "numeric", "infrared_five_eight" => "numeric", "infrared_eight_zero" => "numeric",
        "infrared_J" => "numeric", "infrared_H" => "numeric", "infrared_K" => "numeric", "radio_one_four" => "numeric");

        $result = array();

        $valid_arg = true;
        $is_valid = false;
        foreach($fields as $key => $value) {
            $is_valid = true;
            if (!isset($arr->{$key})) {
                array_push($errors, new ErrorObject("Invalid argument", "{$key} not found!"));
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
                    case "numeric":
                        $is_valid = is_numeric($arr->{$key}) ? true : false;
                        if ($is_valid)
                            $arr->{$key} = (float)$arr->{$key};
                        break; 
                    default:
                        $is_valid = false;
                }

                if (!$is_valid) {
                    $valid_arg = false;
                    array_push($errors, new ErrorObject("Invalid argument", "Expected {$key} to be {$value}."));
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

    public static function Die($reponse_code, $response, $error = NULL) {
        if ($error != NULL)
            array_push($response[ERRORS], $error);
        http_response_code($reponse_code);
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit();
    }
};
?>