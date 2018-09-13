<?php

require_once __DIR__ . '/vendor/autoload.php';

use Luce\TrafficCatcher;

echo " [x] Sending task \n";

$beforetime = microtime(true);
//file_put_contents('log.txt', file_get_contents('php://input'));
TrafficCatcher::toService('rabbit', 5672, 'guest', 'guest')->catchTraffic();
$afterTime = microtime(true);

$timeElapsed = $afterTime - $beforetime;
echo " Elapsed time catching traffic {$timeElapsed}";

echo " [x] Sent \n";

$channel->close();
$connection->close();
