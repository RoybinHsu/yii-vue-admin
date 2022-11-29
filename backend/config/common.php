<?php

use app\utils\helper\Helper;
use yii\i18n\PhpMessageSource;
use yii\rbac\DbManager;
use yii\redis\Cache;

require_once __DIR__ . '/helper.php';
$params   = require __DIR__ . '/params.php';
$db       = require __DIR__ . '/db.php';
$redis    = require __DIR__ . '/redis.php';
$queue    = require __DIR__ . '/queue.php';
$timeZone = 'Asia/Shanghai'; // Asia/Shanghai
try {
    $logDate = (new DateTime('now', new DateTimeZone($timeZone)))->format('Y-m-d');
} catch (Exception $e) {
    $logDate = date('Y-m-d');
}
$components = [
    'helper'      => [
        'class' => Helper::class,
    ],
    'authManager' => [
        'class'        => DbManager::class,
        "defaultRoles" => ["guest"],
        'cache'        => 'cache',
    ],
    'cache'       => [
        'class' => Cache::class,
        'redis' => 'redis_cache',
    ],
    'i18n'        => [
        'translations' => [
            'rbac-admin' => [
                'class'          => PhpMessageSource::class, //'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath'       => '@mdm/admin/messages',
            ],
        ],
    ],
];
