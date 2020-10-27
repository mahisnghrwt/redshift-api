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
define("username", "");
define("password", "");
define("port", 3306);
define("databasename", "");

//Router
define('BASEPATH','/redshift/api/');

define("SCRIPT_PATH", "//web//staff/redshift//storage//app//public//scripts//");
define("ARG_PATH", "//web//staff/redshift//api//log//arg//");
define("ERROR_PATH", "//web//staff/redshift//api//log//error//");
define("OUTPUT_PATH", "//web//staff/redshift//api//log//output//");

// define("SCRIPT_PATH", "d://xampp//htdocs//php7www//redshift-api//scripts//");
// define("ARG_PATH", "d://xampp//htdocs//php7www//redshift-api//log//arg//");
// define("ERROR_PATH", "d://xampp//htdocs//php7www//redshift-api//log//error//");
// define("OUTPUT_PATH", "d://xampp//htdocs//php7www//redshift-api//log//output//");
?>
