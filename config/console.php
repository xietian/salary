<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

if (defined('YII_ENV_PROD') && YII_ENV_PROD) {//产品环境
    $params = require(__DIR__ . '/params.php');
    $db = require(__DIR__ . '/db.php');
} else if (defined('YII_ENV_TEST') && YII_ENV_TEST) { //测试环境
    $db = require(__DIR__ . '/db_test.php');
    $params = require(__DIR__ . '/params_test.php');
} else if (defined('YII_ENV_DEV') && YII_ENV_DEV) { //开发环境
    $db = require(__DIR__ . '/db_dev.php');
    $params = require(__DIR__ . '/params_dev.php');
}




$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 6,
        ],
    ],
    'params' => $params,
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
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
