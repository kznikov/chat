<?php

declare(strict_types = 1);

use Monolog\Logger;
use App\Lib\Resources\MySQL;
use App\Lib\Resources\Redis;
use App\Lib\Misc\DockerMonologHandler;

// Settings
$container->replace([
    'settings' => require __DIR__ . '/settings.php',
]);

// Log
$container->singleton('log', function () use ($container) {
    return (new Logger(gethostname()))
        ->pushHandler(new DockerMonologHandler());
});

// MySQL
$container->singleton('mysql', function () use ($container) {
    return MySQL::getInstance($container->get('settings')['db']);
});

// Redis
$container->singleton('redis', function () use ($container) {
    return Redis::getInstance($container->get('settings')['redis']);
});

