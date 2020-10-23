<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once ('database.php');
require_once('calculator.php');
require_once('globals.php');
require_once('utility.php');
require_once(__DIR__ . '/__config.php');

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USERNAME, RABBITMQ_PASSWORD);
$channel = $connection->channel();
# Create the queue if it doesnt already exist.
$channel->queue_declare(
    $queue = RABBITMQ_QUEUE_NAME,
    $passive = false,
    $durable = true,
    $exclusive = false,
    $auto_delete = false,
    $nowait = false,
    $arguments = null,
    $ticket = null
);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

//Callback function
$callback = function($msg){
    $job_s = json_decode($msg->body);

    echo "Is message redilvered: ";
    echo $msg->delivery_info['redelivered']  == 1 ? "TRUE": "FALSE";
    echo PHP_EOL;
    
    //Get the script name from the database
    $database = new Database_mysqli();
    $script_path = $job_s[0]->script_path;

    //Mark these calculations as PROCESSING in database
    $database->UpdateStatus($job_s, "PROCESSING");

    //Perform the calculation, get the result as an array
    $result_s = Calculator::PerformCalc($script_path, $job_s);
    
    if ($result_s != NULL) {
        $database->UpdateStatus($job_s, "COMPLETED");
        $input_size = count($job_s);
        $result_s_size = 0;
        if (!is_null($result_s["redshift_result"]))
            $result_s_size = count($result_s["redshift_result"]);
        else
            $result_s_size = count($result_s["redshift_alt_result"]);

        if (!is_null($result_s["redshift_result"]))
            for ($i = 0; $i < $result_s_size; $i++) {
                $job_s[$i]->result = $result_s["redshift_result"][$i];
                $job_s[$i]->redshift_alt_result = NULL;
            }

        if (!is_null($result_s["redshift_alt_result"])) {
            for ($i = 0; $i < $result_s_size; $i++) {
                $job_s[$i]->result = NULL;
                $job_s[$i]->redshift_alt_result = $result_s["redshift_alt_result"][$i];
            }
        }

        #add the result into the database
        $database->InsertIntoCalculations($job_s);
        echo " [x] Done", "\n";
    }
    else {
        if ($msg->delivery_info['redelivered'] == 1) {
            $database->UpdateStatus($job_s, "FAILED");
        }
        else {
            echo "Error occured" . PHP_EOL;
            $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], false, true);
            return;
        }
    }
   
    echo json_encode($job_s, JSON_PRETTY_PRINT);  
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume(
    $queue = RABBITMQ_QUEUE_NAME,
    $consumer_tag = '',
    $no_local = false,
    $no_ack = false,
    $exclusive = false,
    $nowait = false,
    $callback
);

while (count($channel->callbacks)) 
{
    $channel->wait();
}

$channel->close();
$connection->close();
?>