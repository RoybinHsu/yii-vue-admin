<?php

use yii\redis\Connection;

return [
    'redis' => [
        'class'    => Connection::class,
        'hostname' => 'redis',
        'port'     => '6379',
        'password' => '123456',
        'database' => 0,
    ],
    'redis_queue' => [
        'class'    => Connection::class,
        'hostname' => 'redis',
        'port'     => '6379',
        'password' => '123456',
        'database' => 1,
    ],
];
