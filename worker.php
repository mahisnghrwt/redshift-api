<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once ('database.php');
require_once('calculator.php');
require_once(__DIR__ . '/config/rabbitmq-config.php');

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
    //echo " [x] Received ", $msg->body, "\n";
    $job_s = json_decode($msg->body);

    //Get the script name from the database
    $database = new Database_mysqli();
    $fprefix = $job_s[0]->galaxyID;
    $scriptPath = $job_s[0]->scriptPath;

    //TODO: Mark the calculations as PROCESSING in the redshift table

    //Perform the calculation, get the result as an array
    $result_s = Calculator::PerformCalc($job_s, $scriptPath, $fprefix);
    $result_s_size = count($result_s);

    //Add a new key-value pair of 'result' to the job_s array
    for ($i = 0; $i < $result_s_size; $i++) {
        $job_s[$i]->result = $result_s[$i];
    }

    
    echo json_encode($job_s, JSON_PRETTY_PRINT);

    //Enter the result into the database
    $database->InsertResult($job_s);

    //TODO: Mark the calculations as COMPLETED in the redshift table

    echo " [x] Done", "\n";
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