<?php

use yii\caching\FileCache;
use app\models\User;
use app\modules\api\Module;
use yii\web\JsonParser;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Mm7HG_llA-d5RvvisiS70_GaDb7TNA-f',
            'parsers' => [
                'application/json' => JsonParser::class
            ]
        ],
        /*'cache' => [
            'class' => FileCache::class,
        ],*/
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'requests' => 'api/message/request',
                'requests/<id>' => 'api/message/request',
                '<_a:(.*)>' => 'api/message/not-found',
                /*[
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => ['api/message'],
                ],*/
                /*[
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => ['api/message'],
                    'extraPatterns' => [
                        'GET requests' => 'api/message/request',
                        'GET requests/<id>' => 'request',
                        'POST requests' => 'requests',
                        'POST requests/<id>' => 'set-comment',
                    ],
                ],*/
                /*[
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => ['api/message'],
                    'extraPatterns' => [
                        'GET request' => 'request',
                        'GET request/<id>' => 'request',
                    ]
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => ['api/message'],
                    'extraPatterns' => [
                        'POST requests' => 'requests',
                        'POST requests/<id>' => 'set-comment',
                    ],
                ],*/
            ],
        ],
    ],
    'modules' => [
        'api' => [
            'class' => Module::class,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
