<?php

use app\utils\log\AppLogTarget;

$timeZone = $queue = $logDate = $db = $params = $redis = $components = null;
require_once __DIR__ . '/common.php';

$config = [
    'id'                  => 'basic-console',
    'timezone'            => $timeZone,
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => array_merge(['log'], array_keys($queue)),
    'controllerNamespace' => 'app\commands',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components'          => $components + [
            'log'    => [
                'traceLevel' => 3,
                'targets'    => [
                    [
                        'class'       => AppLogTarget::class,
                        'levels'      => ['error', 'warning', 'info'],
                        'logVars'     => ['*'],
                        'categories'  => ['application'],
                        'logFile'     => '@runtime/logs/console/stdout-' . $logDate . '.log',
                        'maxLogFiles' => 20,
                        'maxFileSize' => 20480,
                    ],
                    [
                        'class'       => AppLogTarget::class,
                        'levels'      => ['error', 'warning'],
                        'logVars'     => ['*'],
                        'categories'  => ['yii\db\*'],
                        'logFile'     => '@runtime/logs/db/stdout-' . $logDate . '.log',
                        'maxLogFiles' => 20,
                        'maxFileSize' => 20480,
                    ],
                ],
            ],
        ] + $redis + $queue + $db,
    'params'              => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
