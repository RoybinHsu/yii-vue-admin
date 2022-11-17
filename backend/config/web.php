<?php

use app\utils\helper\Helper;
use app\utils\log\AppLogTarget;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtValidationData;
use yii\symfonymailer\Mailer;
use yii\web\JsonParser;
use yii\web\Request;
use yii\web\Response;

$timeZone = $queue = $logDate = $db = $params = $redis = null;
require_once __DIR__ . '/common.php';

$config = [
    'id'           => 'basic',
    'language'     => 'zh-CN',
    'timezone'     => $timeZone,
    'basePath'     => dirname(__DIR__),
    'bootstrap'    => array_merge(['log'], array_keys($queue)),
    'aliases'      => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'defaultRoute' => '/site/index',
    'components'   => [
            'request'      => [
                'class'               => Request::class,
                'parsers'             => [
                    'application/json' => JsonParser::class,
                ],
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => 'dbHQUrLNrhmpLffQQ2Fij3gnRSr7NUcK',
            ],
            'response'     => [
                'class'         => Response::class,
                'format'        => Response::FORMAT_JSON,
                'on beforeSend' => '\app\utils\jwt\Response::beforeSend',
                'charset'       => 'UTF-8',
            ],
            'jwt'          => [
                'class'             => Jwt::class,
                'key'               => '6Yhf4ZwqQUWyROLT',
                'jwtValidationData' => JwtValidationData::class,
            ],
            'helper'       => [
                'class' => Helper::class,
            ],
            'cache'        => [
                'class' => 'yii\caching\FileCache',
            ],
            'user'         => [
                'identityClass'   => app\models\User::class,
                'enableAutoLogin' => true,
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'mailer'       => [
                'class'            => Mailer::class,
                'viewPath'         => '@app/mail',
                // send all mails to a file by default.
                'useFileTransport' => true,
            ],
            'log'          => [
                'traceLevel' => 3,
                'targets'    => [
                    [
                        'class'       => AppLogTarget::class,
                        'levels'      => ['error', 'warning', 'info'],
                        'logVars'     => ['*'],
                        'categories'  => ['application'],
                        'logFile'     => '@runtime/logs/web/stdout-' . $logDate . '.log',
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
            'urlManager'   => [
                'enablePrettyUrl' => true,
                'showScriptName'  => false,
                'rules'           => [
                ],
            ],
            'jwt'          => [
                'class'             => Jwt::class,
                'key'               => '6Yhf4ZwqQUWyROLT',
                'jwtValidationData' => JwtValidationData::class,
            ],

        ] + $redis + $queue + $db,
    'params'       => $params,
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class'      => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
