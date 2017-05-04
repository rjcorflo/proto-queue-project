<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

echo " [x] Sending task \n";

$beforetime = microtime(true);
TrafficCatcher::toService('rabbit', 5672, 'guest', 'guest')->catchTraffic();
$afterTime = microtime(true);

echo " Elapsed time catching traffic {$afterTime - $beforetime}";

echo " [x] Sent \n";

$channel->close();
$connection->close();
