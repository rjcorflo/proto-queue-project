<?php
namespace Luce;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class TrafficCatcher
{
    private $connection;

    private $channel;

    private function __construct($server, $port, $user, $pass)
    {
        $this->connection = new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
    }

    public static function toService($server, $port, $user, $pass)
    {
        return new self($server, $port, $user, $pass);
    }

    public function catchTraffic()
    {
        try {
            $this->channel->queue_declare('task_queue', false, false, false, false);

            $cookieString = json_encode($_COOKIE);
            $input = file_get_contents('php://input');

            $msg = new AMQPMessage("{$cookieString}*-*-*{$input}");
            $this->channel->basic_publish($msg, '', 'task_queue');
        } finally {
            $this->channel->close();
            $this->connection->close();
        }
    }
}
