<?php
require_once('utility.php');

class Calculator {
    public static function PerformCalc($job_s, $scriptPath, $prefix) {
        $result_s = array();

        $fname = 'scripts/' . (string)$prefix . "_args.json";
        $fp = fopen($fname, 'w');
        fwrite($fp, json_encode($job_s));
        fclose($fp);

        $commandPrefix = "python ";

        //Declare the command to execute in the shell
        $command = $commandPrefix . $scriptPath . " " . $fname;
        $command = escapeshellcmd($command);

        //Get the result from shell
        $output =  shell_exec($command);

        $result_s = preg_split('/\s+/', trim($output));

        return $result_s;
    }
}

?>