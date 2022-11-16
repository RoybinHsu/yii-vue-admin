<?php

use yii\queue\LogBehavior;
use yii\queue\redis\Queue;
use yii\queue\serializers\JsonSerializer;

return [
    'queue' => [
        'class'      => Queue::class,
        'redis'      => 'redis_queue',
        'channel'    => 'FLY:queue',
        'as log'     => LogBehavior::class,
        'serializer' => JsonSerializer::class,
    ],
];
