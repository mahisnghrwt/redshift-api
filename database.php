<?php
require_once(__DIR__ . '/__config.php');

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

    public function InsertIntoCalculations($calculations) {
        $sql = "INSERT INTO calculations(galaxy_id, method_id, redshift_result) VALUES ";
        foreach($calculations as $x) {
            $sql .= "({$x->galaxy_id}, {$x->method_id}, {$x->result}), ";
        }
        $sql = substr($sql, 0, strlen($sql) - 2);

        if ($this->connection->query($sql) === FALSE)
            echo $this->connection->error;
    }

    public function CheckUserExists($email) {
        $email = $this->connection->escape_string($email);
        $sql = "SELECT 'id' FROM users WHERE email = '$email'";
        if ($result = $this->connection->query($sql)) {
            if ($result->num_rows == 1)
                return true;
            else
                return false;
        }

        return false;
    }

    function InsertIntoRedshift($data, $job_id, $status, $size = -1) {
        $first_id = -1;
        $size = $size == -1 ? count($data): $size;
    
        $columns = array("assigned_calc_id", "optical_u", "optical_v", "optical_g", "optical_r", "optical_i", "optical_z", "infrared_three_six",
        "infrared_four_five", "infrared_five_eight", "infrared_eight_zero", "infrared_j", "infrared_h", "infrared_k", "radio_one_four",
        "status", "job_id");

        $sql = "INSERT INTO redshifts (";
        foreach ($columns as $c) {
            $sql .= $c . ", ";
        }
        $sql = substr($sql, 0, strlen($sql) - 2);
        $sql .= ") VALUES ";

        for ($i = 0; $i < $size; $i++) {
            $sql .= "({$data[$i]->assigned_calc_ID}, {$data[$i]->optical_u}, {$data[$i]->optical_v}, {$data[$i]->optical_g}, {$data[$i]->optical_r}, {$data[$i]->optical_i},
            {$data[$i]->optical_z}, {$data[$i]->infrared_three_six}, {$data[$i]->infrared_four_five}, {$data[$i]->infrared_five_eight}, {$data[$i]->infrared_eight_zero},
            {$data[$i]->infrared_J}, {$data[$i]->infrared_H}, {$data[$i]->infrared_K}, {$data[$i]->radio_one_four}, '{$status}', {$job_id}), ";
        }

        $sql = substr($sql, 0, strlen($sql) - 2);
        if ($this->connection->query($sql) === TRUE) {
            $first_id =  $this->connection->insert_id;
        }
        else {
            //TODO: this must be removed
            echo "Error: " . $this->connection->error;
        }

        return $first_id;
    }

    function SelectStatus($calculation_ids) {
        $status = array();
        $l = count($calculation_ids);

        $sql = "SELECT calculation_id, status from redshifts where calculation_id IN (";
        for ($i = 0; $i < $l; $i++) {
            $sql .= (string)$calculation_ids[$i] . ", ";
        }
        $sql = substr($sql, 0, strlen($sql) - 2);
        $sql .= ")";

        $sqlResult = $this->connection->query($sql);
        if ($sqlResult == NULL || $sqlResult->num_rows == 0) {
            return NULL;
        }

        while($row = $sqlResult->fetch_assoc()) {
            $status[$row['calculation_id']] = $row['status'];
        }

        return $status;
    }

    function SelectSingleStatus($calculation_id) {
        $status = "";
        $sql = "SELECT calculation_id, status from redshifts where calculation_id = {$calculation_id} limit 1";
        $sqlResult = $this->connection->query($sql);
        if ($sqlResult == NULL || $sqlResult->num_rows == 0) {
            return NULL;
        }

        while($row = $sqlResult->fetch_assoc()) {
            $status = $row['status'];
        }

        return $status;
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

    function UpdateStatus($calculations, $status) {
        $sql = "UPDATE redshifts SET status = '{$status}' WHERE calculation_id IN (";
        foreach ($calculations as $x) {
            $sql .= $x->galaxy_id . ", ";
        }

        $sql = substr($sql, 0, strlen($sql) - 2);
        $sql .= ")";

        if ($this->connection->query($sql) === FALSE) {
            echo $this->connection->error;
        }
    }

}
?>