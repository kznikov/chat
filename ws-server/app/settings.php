<?php

declare(strict_types = 1);

return [
    'server' => [
        'host' => '0.0.0.0',
        'port' => 9502,
    ],
    'db'     => [
        'host'     => getenv('MYSQL_HOST', false),
        'username' => getenv('MYSQL_USER', false),
        'password' => getenv('MYSQL_PASSWORD', false),
        'database' => getenv('MYSQL_DATABASE', false),
    ],
    'redis' => [
        'host' => getenv('REDIS_HOST', false),
        'port' => getenv('REDIS_PORT', false),
        'password' => getenv('REDIS_PASSWORD', false),
    ],
];
