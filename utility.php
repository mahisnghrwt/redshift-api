<?php
define("METHODS", "methods");
define("TOKEN", "token");
define("JOB_ID", "job_ID");


class Utility {
    public static function GetArgs($arr, &$response) {
        $validation = array("optical_u" => "float", "optical_v" => "float",
        "optical_g" => "float", "optical_r" => "float",
        "optical_i" => "float", "optical_z" => "float",
        "infrared_three_six" => "float", "infrared_four_five" => "float",
        "infrared_five_eight" => "float", "infrared_eight_zero" => "float",
        "infrared_J" => "float", "infrared_H" => "float",
        "infrared_K" => "float", "radio_one_four" => "float");
        $result = array();

        foreach($validation as $key => $value) {
            $isValid = true;
            switch($value) {
                case "string":
                    if (!is_string($arr->$key)) {
                        $isValid = false;
                    }
                break;
                case "int":
                    if (!is_int($arr->$key)) {
                        $isValid = false;
                    }
                break;    
                case "float":
                    if (!is_float($arr->$key)) {
                        $isValid = false;
                    }
                break; 
                default:
                    $isValid =  false;
            }

            if (!$isValid) {
                ErrorHandler::LogError($response, new ErrorObject("Arg error", "{$arr->$key} is in incorrect format."));
                //echo $key . " in invalid data-type";
                return NULL;
            }
            else {
                $result[$key] = $arr->$key;
            }
        }
        return $result;
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

    public static function ExtractMetaData($argMetaData, &$response, $isGuest = false) {
        $metaData = array();

        if (array_key_exists("job_id", $argMetaData)) {
            $metaData["job_id"] = $argMetaData->job_id;
        }
        else {
            array_push($response->ERRORS, new ErrorObject("Invalid MetaData", "job_id not passed."));
            return null;
        }

        if (array_key_exists("methods", $argMetaData)) {
            $metaData["methods"] = $argMetaData->methods;
        }
        else {
            array_push($response->ERRORS, new ErrorObject("Invalid MetaData", "methods not passed."));
            return null;
        }

        if (!$isGuest) {
            if (array_key_exists("token", $argMetaData)) {
                $metaData["token"] = $argMetaData->token;
            }
            else {
                array_push($response->ERRORS, new ErrorObject("Invalid MetaData", "token not passed."));
                return null;
            }
        }

        return $metaData;
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