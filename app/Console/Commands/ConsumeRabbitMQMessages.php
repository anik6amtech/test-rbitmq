<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConsumeRabbitMQMessages extends Command
{
    protected $signature = 'rabbitmq:consume';
    protected $description = 'Consume messages from RabbitMQ queues';
    protected RabbitMQService $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        parent::__construct();
        $this->rabbitMQService = $rabbitMQService;
    }

    public function handle()
    {
        $selectedQueueCallbacks = [

            'Queue1' => function ($msg) {
                Log::info("Queue1: " . $msg->body);
                $this->test('Queue1',$msg);
            },
            'Queue2' => function ($msg) {
                Log::info("Queue2: " . $msg->body);
                $this->test('Queue2',$msg);
            },
            'Queue3' => function ($msg) {
                Log::info("Queue3: " . $msg->body);
                $this->test('Queue3',$msg);
            },
        ];

        if (empty($selectedQueueCallbacks)) {
            $this->error("No valid queues specified.");
            return;
        }

        $this->rabbitMQService->consume($selectedQueueCallbacks);
    }
    public function test($queue,$msg) {
        echo "Received message ".$queue.": " . $msg->body . "\n";
    }
}
