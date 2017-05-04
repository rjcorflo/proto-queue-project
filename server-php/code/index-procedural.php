<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

echo " [x] Sending task \n";

$beforetime = microtime(true);

$connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, false, false, false);

$cookieString = json_encode($_COOKIE);
$input = file_get_contents('php://input');

$msg = new AMQPMessage("{$cookieString}*-*-*{$input}");
$channel->basic_publish($msg, '', 'task_queue');

$channel->close();
$connection->close();

$afterTime = microtime(true);

echo " Elapsed time catching traffic {$afterTime - $beforetime}";

echo " [x] Sent \n";

