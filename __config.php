<?php

//Authenticator config
define("cipherMethod", "aes-128-cbc");
define("key", "5rCBIs9Km!!cacr1");
define("iv", "123hasdba036vpax");

//RabbitMQ config
define("RABBITMQ_HOST", "localhost");
define("RABBITMQ_PORT", 5672);
define("RABBITMQ_USERNAME", "guest");
define("RABBITMQ_PASSWORD", "guest");
define("RABBITMQ_QUEUE_NAME", "task_queue");

//Database config
define("servername", "localhost");
define("username", "root");
define("password", "");
define("port", 3306);
define("databasename", "ps2035");
?>