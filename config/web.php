<?php
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
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Chongqing',
    'name'=>'资源管理系统',
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'app\modules\v1\Module',
            'components' => [
                'errorHandler' => [
                    'errorAction' => 'v1/error/show',
                    'class' => 'yii\web\ErrorHandler',
                ]
            ]
        ],
        "admin" => [
            "class" => 'mdm\admin\Module',
        ],
    ],
    "aliases" => [
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin",
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '88CXJg3vfe8XPrZAUQer_auVRMqAM9OQ',
            "enableCsrfValidation" => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => true,
            'forceCopy' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\UserAuth',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',//wx/error/notfound
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'fileStorage' => [
            'class' => 'trntv\filekit\Storage',
            'baseUrl' => "@web/upload/image",
            'filesystem' => function () {
                $adapter = new \League\Flysystem\Adapter\Local("upload/image");
                return new League\Flysystem\Filesystem($adapter);
            }
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => [
//                        'yii\db\*',
//                        'app\models\*',
                        'yii\*'
                    ],
                    'logFile' => '@app/runtime/logs/app.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'categories' => [
//                        'yii\db\*',
//                        'app\models\*',
                        'yii\*'
                    ],
                    'logFile' => '@app/runtime/logs/db.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
            ],

        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource'
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 6,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
            "defaultRoles" => ["guest"],
        ],
        // 'view' => [
        //     'theme' => [
        //         'pathMap' => [
        //             '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
        //         ],
        //     ],
        // ],
    ],
    'params' => $params,
    'as access' => [
        //ACF肯定是要加的，因为粗心导致该配置漏掉了，很是抱歉
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => ['wx/*', 'v1/*', 'site/*'
            //这里是允许访问的action
            //controller/action
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
