<?php

declare(strict_types = 1);

return [
    'jwt'   => [
        'secret' => getenv('JWT_SECRET'),
    ],
    'cors_domains' => ['http://localhost:3000'],
    'db'    => [
        'host'     => getenv('MYSQL_HOST'),
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'database' => getenv('MYSQL_DATABASE'),
    ],
    'redis' => [
        'host'     => getenv('REDIS_HOST'),
        'port'     => getenv('REDIS_PORT'),
        'password' => getenv('REDIS_PASSWORD'),
    ],
];
