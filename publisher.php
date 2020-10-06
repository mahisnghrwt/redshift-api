<?php
require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/__config.php');

class Publisher {
    //Declare the channel, it will be use to send msgs
    public $channel;

    //Constructor
    function __construct() {
        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USERNAME, RABBITMQ_PASSWORD);

        $this->channel = $connection->channel();

        //Create the queue if it does not already exist.
        $this->channel->queue_declare(
            $queue = RABBITMQ_QUEUE_NAME,
            $passive = false,
            $durable = true,
            $exclusive = false,
            $auto_delete = false,
            $nowait = false,
            $arguments = null,
            $ticket = null
        );
    }

    //Takes assosciate array as an argument
    //Send it to the message broker
    public function Send($jobArray) {
        $msg = new \PhpAmqpLib\Message\AMQPMessage(
            json_encode($jobArray, JSON_UNESCAPED_SLASHES),
            array('delivery_mode' => 2) # make message persistent
        );
    
        $this->channel->basic_publish($msg, '', RABBITMQ_QUEUE_NAME);
    }
}

?>