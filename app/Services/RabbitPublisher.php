<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitPublisher
{
    public static function publish($queue, $data): void
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        $message = new AMQPMessage(json_encode($data), ['delivery_mode' => 2]);
        $channel->basic_publish($message, '', $queue);

        $channel->close();
        $connection->close();
    }
}
