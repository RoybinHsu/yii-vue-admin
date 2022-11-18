<?php

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
