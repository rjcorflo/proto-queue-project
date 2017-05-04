<?php
namespace Luce;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class TrafficCatcher
{  
    private $server;
    private $port;
    private $user;
    private $pass;

    private function __construct($server, $port, $user, $pass)
    {
        $this->server = $server;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
    }

    public static function toService($server, $port, $user, $pass)
    {
        return new self($server, $port, $user, $pass);
    }

    public function catchTraffic()
    {
        $connection = new AMQPStreamConnection($this->server, $this->port, $this->user, $this->pass);
        $channel = $connection->channel();
        $channel->queue_declare('task_queue', false, false, false, false);

        $cookieString = json_encode($_COOKIE);
        $input = file_get_contents('php://input');

        $msg = new AMQPMessage("{$cookieString}*-*-*{$input}");
        $channel->basic_publish($msg, '', 'task_queue');

        $channel->close();
        $connection->close();
    }
}
