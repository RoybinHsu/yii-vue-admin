<?php

return [
    'db' => [
        'class'    => 'yii\db\Connection',
        'dsn'      => 'mysql:host=mysql;dbname=fly',
        'username' => 'root',
        'password' => '123456',
        'charset'  => 'utf8',
        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
    ],
];
