<?php
require_once(__DIR__ . '/config/database-config.php');

class Database_mysqli {
    //MySQLi Connection object
    private $connection = null;
    
    //Connect to the database when object instance is created
    function __construct() {
        $this->connection = new mysqli(servername, username, password, databasename);

        if ($this->connection->connect_error)
            die("Connection failed: " . $connection->connect_error);
    }

    //Close the connection, when instance is destroyed
    function __desctruct() {
        $connection->close();
    }


    /*
    Database get values
    Database insert values (get AI)
    Database Find
    */
    public function InsertResult($data) {
        $size = count($data);
        $sql_prefix = "INSERT INTO `calculations`(`galaxy_id`, `method_id`, `redshift_result`) VALUES";
        $values = "";

        for ($i = 0; $i < $size - 1; $i++) {
            $values .= "({$data[$i]->galaxyID}, {$data[$i]->methodID}, {$data[$i]->result}), ";
        }

        $values .= "({$data[$size - 1]->galaxyID}, {$data[$size - 1]->methodID}, {$data[$size - 1]->result})";


        $sql = $sql_prefix . $values;
        if ($this->connection->query($sql) === TRUE) {

        }
        else {
            //echo "Error: " . $this->connection->error;
        }

    }

    public function checkUserExists($user_email) {
        $sql = "SELECT 'id' FROM users WHERE email = '$user_email'";
        if ($result = $this->connection->query($sql)) {
            if ($result->num_rows == 1)
                return true;
            else
                return false;
        }
        else
            //echo "Some problem with the query!" . PHP_EOL;
        return false;
    }
    
    function insertRedshift($data, $jobID) {
        $firstID = -1;
        $status = "pending";
        $size = count($data);
        $sql_prefix = "INSERT INTO `redshifts`(`assigned_calc_id`, `optical_u`, `optical_v`, `optical_g`, `optical_r`, `optical_i`, `optical_z`, `infrared_three_six`, `infrared_four_five`, `infrared_five_eight`, `infrared_eight_zero`, `infrared_J`, `infrared_H`, `infrared_K`, `radio_one_four`, `job_ID`, `status`) VALUES";
        $values = "";

        for ($i = 0; $i < $size - 1; $i++) {
            $values .= "({$data[$i]->assigned_calc_ID}, {$data[$i]->optical_u}, {$data[$i]->optical_v}, {$data[$i]->optical_g}, {$data[$i]->optical_r}, {$data[$i]->optical_i},
            {$data[$i]->optical_z}, {$data[$i]->infrared_three_six}, {$data[$i]->infrared_four_five}, {$data[$i]->infrared_five_eight}, {$data[$i]->infrared_eight_zero}, {$data[$i]->infrared_J}, {$data[$i]->infrared_H}, {$data[$i]->infrared_K}, {$data[$i]->radio_one_four}, {$jobID}, 'pending'), ";
        }

        $values .= "({$data[$size - 1]->assigned_calc_ID}, {$data[$size - 1]->optical_u}, {$data[$size - 1]->optical_v}, {$data[$size - 1]->optical_g}, {$data[$size - 1]->optical_r}, {$data[$size - 1]->optical_i},
        {$data[$size - 1]->optical_z}, {$data[$size - 1]->infrared_three_six}, {$data[$size - 1]->infrared_four_five}, {$data[$size - 1]->infrared_five_eight}, {$data[$size - 1]->infrared_eight_zero}, {$data[$size - 1]->infrared_J}, {$data[$size - 1]->infrared_H}, {$data[$size - 1]->infrared_K}, {$data[$size - 1]->radio_one_four}, {$jobID}, 'pending')";


        $sql = $sql_prefix . $values;
        if ($this->connection->query($sql) === TRUE) {
            $firstID =  $this->connection->insert_id;
        }
        else {
            //echo "Error: " . $this->connection->error;
        }

        return $firstID;
    }

    function Insert($table, $columns, $values, $length, $defaults = NULL) {
        $firstID = -1;
        $columnsCount = count($columns);

        //TODO: Merge the defaults into columns

        if ($columnsCount < $length)
            $length = $columnCount;
        $valuesCount = count($values);
        $sql = "INSERT INTO {$table} (";

        for ($i = 0; $i < $columnsCount; $i++) {
            $sql .= $columns[$i];
            if ($i == $columnsCount - 1) {
                $sql .= ") ";
            }
            else {
                $sql .= ", ";
            }
        }
        $sql .= "values ";
        for ($i = 0; $i < $length; $i++) {
            $sql .= "(";
            for ($j = 0; $j < $columnsCount; $j++) {
                $val = "";
                if (!array_key_exists($columns[$j], $values[$i]))
                    $val = $defaults[$columns[$j]];
                else
                    $val = $values[$i]->{$columns[$j]};

                if (is_string($val)) {
                    $sql .= "'" . $val . "'";
                }
                else
                    $sql .= $val;
                if ($j < $columnsCount - 1) {
                    $sql .= ", ";
                }
            }
            if ($i < $length - 1) {
                $sql .= "), ";
            }
            else {
                $sql .= ")";
            }
        }

        if ($this->connection->query($sql) === TRUE) {
            $firstID =  $this->connection->insert_id;
        }
        else {
            echo "Something went wrong!" . $this->connection->error;
        }

        return $firstID;
    }

    function Select($table, $columns, $where, $key) {
        $result = array();

        $sqlPrefix = "SELECT ";
        $sqlMid = "";
        $sqlSuffix = "from {$table}";
        $columnCount = count($columns);
        for ($i = 0; $i < $columnCount; $i++) {
            $sqlMid .= $columns[$i];
            if ($i != $columnCount - 1) {
                $sqlMid .= ", ";
            }
            else {
                $sqlMid .= " ";
            }
        }

        $sql = $sqlPrefix . $sqlMid . $sqlSuffix;

        if ($where != NULL) {
            foreach ($where as $key => $vals) {
                $sql .= " WHERE {$key} IN (";
                $l = count($vals);
                for ($i = 0; $i < $l; $i++) {
                    $sql .= $vals[$i];
                    if ($i < $l - 1) { 
                        $sql .= ", ";
                    }
                    else
                        $sql .= ")";
                }
            }
        }

        // echo $sql;

        $mysqliResult = $this->connection->query($sql);

        if ($mysqliResult == NULL || $mysqliResult->num_rows == 0) {
            return NULL;
        }
        
        if ($key == NULL) {
            while($row = $mysqliResult->fetch_assoc()) {
                array_push($result, $row);
            }
        }
        else {
            while($row = $mysqliResult->fetch_assoc()) {
                $result[$row[$key]] = $row;
            }
        }

        return $result;
    }

    function GetMethodList() {
        $method_s = array();

        $sql = "SELECT method_id, python_script_path from methods";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $method_s[$row["method_id"]] = $row["python_script_path"];
            }
        }

        return $method_s;
    }
}
?>