<?php
require_once('utility.php');
require_once('__config.php');

define("GRAPH_BASE_URL", "http://redshift-01.cdms.westernsydney.edu.au/redshift/api/graph/");

class Calculator {
    public static function PerformCalc($script, $data) {
        $result_s = array();
        $result_s["redshift_result"] = null;
        $result_s["redshift_alt_result"] = null;

        //Argument JSON file prefix is <galaxy_id>_<method_id>_args.json
        $fPrefix = "";
        if (is_array($data[0]))
            $fPrefix = (string)$data[0]['galaxy_id'] . "_" . (string)$data[0]['method_id'];
        else
            $fPrefix = (string)$data[0]->galaxy_id . "_" . (string)$data[0]->method_id;
        
        $argFileName = $fPrefix . "_args.json";
        //Path + file name
        $argFile = ARG_PATH . $argFileName;
        //Open the file and write all the data as JSON
        $fp = fopen($argFile, 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);

        $script = basename($script);
        $script = SCRIPT_PATH . $script;

        $errorFile = ERROR_PATH . $fPrefix . "_error.txt";
        
        //Command to execute in shell
        $command = "python " . $script . " " . $argFile . " " . OUTPUT_PATH . " 2> " . $errorFile;
        $command = escapeshellcmd($command);

        //Get the result from shell
        $output =  shell_exec($command);

        if ($output == NULL)
            return NULL;

        $outputArr = preg_split('/\s+/', trim($output));
        $outputArrLength = count($outputArr);
        $dataLength = count($data);

        if ($outputArrLength != $dataLength && $outputArr != 2 * $dataLength)
            return NULL;
        
        if ($outputArrLength === 2 * $dataLength) {
            $result_s["redshift_result"] = array();
            $result_s["redshift_alt_result"] = array();
            for ($i = 0; $i < $outputArrLength; $i = $i + 2) {
                array_push($result_s["redshift_result"], $outputArr[$i]);
                array_push($result_s["redshift_alt_result"], $outputArr[$i + 1]);
            }
        }
        else {
            $resultType = "redshift_result";
            if (!is_numeric($outputArr[0])) {
                $resultType = "redshift_alt_result";
                $result_s[$resultType] = array();
                $result_s["redshift_result"] = NULL;
                # Get the extenstion of ouptut file
                # here we are just assuming that the filename = substr(0, strlen(fullfilename) - 4)
                # trim the extension from every single output
                # concatenate the resulting string with the base graph url
                foreach($outputArr as $x) {
                    //Convert this 'file name' to url
                    $x = GRAPH_BASE_URL . substr($x, 0, strlen($x) - 4);
                    array_push($result_s[$resultType], $x);
                }
            }
            else {
                $result_s[$resultType] = array();
                $result_s["redshift_alt_result"] = NULL;
                foreach($outputArr as $x) {
                    array_push($result_s[$resultType], $x);
                }
            }
        }

        return $result_s;
    }
}

?>