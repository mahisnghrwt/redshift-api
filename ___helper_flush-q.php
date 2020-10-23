<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once ('database.php');
require_once('calculator.php');
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
    echo json_encode($job_s, JSON_PRETTY_PRINT);  
    echo " [x] FLUSHED", "\n";
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