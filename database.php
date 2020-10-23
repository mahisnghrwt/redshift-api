<?php
require_once(__DIR__ . '/__config.php');
require_once('globals.php');

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
        $datum = new DateTime();
        $datum->setTimezone(new DateTimeZone('Australia/Sydney'));
        $timestamp = $datum->format('Y-m-d H:i:s');
        $sql = "INSERT INTO calculations(galaxy_id, method_id, redshift_result, redshift_alt_result, created_at) VALUES ";

        foreach($calculations as $x) {
            if (!is_null($x->redshift_alt_result))
                $x->redshift_alt_result = "'" . $x->redshift_alt_result . "'";
            else
                $x->redshift_alt_result = "NULL";

            if (is_null($x->result))
                $x->result = "NULL";

            $sql .= "({$x->galaxy_id}, {$x->method_id}, {$x->result}, {$x->redshift_alt_result}, '{$timestamp}'), ";
        }
        $sql = substr($sql, 0, strlen($sql) - 2);

        if ($this->connection->query($sql) === FALSE)
            echo $this->connection->error;
    }

    public function CheckUserExists($email, $password) {
        $email = $this->connection->escape_string($email);
        $password = $this->connection->escape_string($password);

        $sql = "SELECT 'id' FROM users WHERE email = '$email' AND password = '$password'";
        if ($result = $this->connection->query($sql)) {
            if ($result->num_rows == 1)
                return true;
            else
                return false;
        }

        return false;
    }

    public function GetEmailPassword() {
        $result = array();
        $sql = "SELECT email, password FROM users";
        if ($sqlResult = $this->connection->query($sql)) {
            while($row = $sqlResult->fetch_assoc()) {
                array_push($result, $row);
            }
        }
        return $result;
    }

    function InsertIntoRedshift($data, $job_id, $status, $size = -1) {
        $first_id = -1;
        $size = $size == -1 ? count($data): $size;
        $datum = new DateTime();
        $datum->setTimezone(new DateTimeZone('Australia/Sydney'));
        $timestamp = $datum->format('Y-m-d H:i:s');
    
        $columns = array("assigned_calc_id", "optical_u", "optical_v", "optical_g", "optical_r", "optical_i", "optical_z", "infrared_three_six",
        "infrared_four_five", "infrared_five_eight", "infrared_eight_zero", "infrared_j", "infrared_h", "infrared_k", "radio_one_four",
        "status", "job_id", 'created_at');

        $sql = "INSERT INTO redshifts (";
        foreach ($columns as $c) {
            $sql .= $c . ", ";
        }
        $sql = substr($sql, 0, strlen($sql) - 2);
        $sql .= ") VALUES ";

        for ($i = 0; $i < $size; $i++) {
            $data[$i]->assigned_calc_id = !isset($data[$i]->assigned_calc_id) ? "" : $this->connection->escape_string($data[$i]->assigned_calc_id);

            $sql .= "('{$data[$i]->assigned_calc_id}', {$data[$i]->optical_u}, {$data[$i]->optical_v}, {$data[$i]->optical_g}, {$data[$i]->optical_r}, {$data[$i]->optical_i},
            {$data[$i]->optical_z}, {$data[$i]->infrared_three_six}, {$data[$i]->infrared_four_five}, {$data[$i]->infrared_five_eight}, {$data[$i]->infrared_eight_zero},
            {$data[$i]->infrared_J}, {$data[$i]->infrared_H}, {$data[$i]->infrared_K}, {$data[$i]->radio_one_four}, '{$status}', {$job_id}, '{$timestamp}'), ";
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

    function SelectAllStatus($user_email) {
        $result = array();
        $sql = "SELECT redshifts.calculation_id as 'calculation_id', redshifts.status as 'status' FROM redshifts, users, jobs WHERE redshifts.job_id = jobs.job_id AND jobs.user_id = users.id AND users.email = '{$user_email}'";
        if (is_null($user_email))
            $sql = "SELECT redshifts.calculation_id as 'calculation_id', redshifts.status as 'status' FROM redshifts";
       
        $sqlResult = $this->connection->query($sql);
        if ($sqlResult == NULL || $sqlResult->num_rows == 0)
            return array();

        while($row = $sqlResult->fetch_assoc())
            $result[$row['calculation_id']] = $row['status'];

        return $result;
    }


    function SelectResult($user_email) {
        $result = array();

        $sql = "SELECT calculations.redshift_result as 'redshift_result', calculations.redshift_alt_result as 'redshift_alt_result', calculations.galaxy_id as 'calculation_id', calculations.method_id as 'method_id' from calculations, redshifts, users, jobs where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id AND users.email = '{$user_email}'";
        if (is_null($user_email))
            $sql = "SELECT calculations.redshift_result as 'redshift_result', calculations.redshift_alt_result as 'redshift_alt_result', calculations.galaxy_id as 'calculation_id', calculations.method_id as 'method_id' from calculations";

        $sqlResult = $this->connection->query($sql);
        if ($sqlResult == NULL || $sqlResult->num_rows == 0)
            return array();

        while($row = $sqlResult->fetch_assoc()) {
            if (!isset($result[$row['calculation_id']]))
                $result[$row['calculation_id']] = array();
            
            array_push($result[$row['calculation_id']], $row);
        }

        return $result;
    }

    public function GetMethods() {
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

    function GetMethodList() {
        $method_s = array();

        $sql = "SELECT method_id as 'id', method_name as 'name', method_description as 'desc' from methods";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($method_s, $row);
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

    function GetAuthorizationLevel($email) {
        $authorizationLevel = -1;

        $sql = "SELECT level FROM users WHERE email = '{$email}' LIMIT 1";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $authorizationLevel = $row['level'];
            }
        }

        return $authorizationLevel;
    }
}
?>