<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

echo " [x] Sending task \n";

$connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, false, false, false);

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];

$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

print_r($request);

$input = file_get_contents('php://input');

$msg = new AMQPMessage($input);
$channel->basic_publish($msg, '', 'task_queue');

echo " [x] Sent \n";

$channel->close();
$connection->close();
