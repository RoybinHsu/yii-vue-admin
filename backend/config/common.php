<?php

use app\utils\alibaba\Cuckoo as Cuckoo1688;
use app\utils\helper\Helper;
use app\utils\pdd\Cuckoo as CuckooPdd;

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
    'cuckoo_1688' => [
        'class'        => Cuckoo1688::class,
        'client_id'    => '999999',
        'app_secret'   => 'aabde5de42c679a812',
        'redirect_url' => 'https://fly.com/1688',
    ],
    'cuckoo_pdd'  => [
        'class'        => CuckooPdd::class,
        'client_id'    => '888888',
        'app_secret'   => 'abe81de8d6d6788ec',
        'redirect_url' => 'https://fly.com/pdd',
    ],
];
