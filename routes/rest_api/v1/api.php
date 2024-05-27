<?php

use App\Services\RabbitMQService;
use Illuminate\Support\Facades\Route;

Route::prefix('/tenant/auth')->group(function () {
    // Define your tenant auth routes here
});



Route::get('/publish/{msg}', function ($msg) {
    $message = $msg;
    $exchange = 'microservice.test';
    $rabbitMQService = new RabbitMQService();
    $rabbitMQService->publish(message:$message, exchangeName:$exchange,routingKey:'');

    return response('Message published to RabbitMQ');
});

