<?php

use yii\redis\Connection;

return [
    'redis' => [
        'class'    => Connection::class,
        'hostname' => 'redis',
        'port'     => '3306',
        'password' => '123456',
        'database' => 0,
    ],
    'redis_queue' => [
        'class'    => Connection::class,
        'hostname' => 'redis',
        'port'     => '3306',
        'password' => '123456',
        'database' => 1,
    ],
];
