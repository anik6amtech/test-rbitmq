<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        $this->channel = $this->connection->channel();
    }

    public function publish(string $message, string $exchangeName, ?string $routingKey = '')
    {
        try {
            $this->channel->exchange_declare($exchangeName, 'direct', true, false, false);

            $msg = new AMQPMessage($message);
            $this->channel->basic_publish($msg, $exchangeName, $routingKey);

            Log::info("Message sent to $exchangeName with routing key $routingKey: $message");
        } catch (\Throwable $e) {
            Log::error("Error publishing message: " . $e->getMessage());
        }
    }

    public function consume(array $queueCallbacks)
    {
        try {
            foreach ($queueCallbacks as $queueName => $callback) {
                $this->channel->queue_declare($queueName, false, true, false, false);
                $this->channel->basic_consume($queueName, '', false, true, false, false, $callback);
            }

            Log::info("Waiting for messages. To exit press CTRL+C");

            while ($this->channel->is_consuming()) {
                $this->channel->wait();
            }
        } catch (\Throwable $e) {
            Log::error("Error consuming messages: " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
