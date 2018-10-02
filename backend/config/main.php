<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'eav_module'],
    'modules' => [
        'treemanager' => [
            'class' => '\common\modules\category\Module'
        ],
        'menu_module' => [
            'class' => '\common\modules\menu\Module',
            'components' => [
                'menu_manager' => '\common\modules\menu\Manager'
            ]
        ],
        'settings_module' => [
            'class' => '\common\modules\settings\Module',
        ],
        'newsletter_module' => [
            'class' => '\common\modules\newsletter\Module',
            'components' => [
                'newsletter_model' => '\common\models\Newsletter',
                'newsletter_facade' => '\common\modules\newsletter\Newsletter'
            ]
        ]
    ],
    'components' => [
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
        ],
        'request' => [
            'class' => 'common\components\Request',
            'csrfParam' => '_csrf-backend',
            'web' => '/backend/web',
            'adminUrl' => '/iza-admin',
            'csrfCookie' => [
                'httpOnly' => true,
                'path' => '/iza-admin'
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'cookieParams' => [
                'path' => '/iza-admin'
            ],
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'frontendUrlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'https://wol.iza.org',
            'rules' => [
                'articles/<slug:[0-9a-zA-Z-]+>/v<v:[\d]+>' => 'article/one-pager',
                'articles/<slug:[0-9a-zA-Z-]+>' => 'article/one-pager',
                'articles/<slug:[0-9a-zA-Z-]+>/v<v:[\d]+>/long' => 'article/full',
                'articles/<slug:[0-9a-zA-Z-]+>/long' => 'article/full',
                'articles/<slug:[0-9a-zA-Z-]+>/v<v:[\d]+>/map' => 'article/map',
                'articles/<slug:[0-9a-zA-Z-]+>/map' => 'article/map',
                'articles/<slug:[0-9a-zA-Z-]+>/v<v:[\d]+>/lang/<code:[a-z]{2}>' => 'article/lang',
                'articles/<slug:[0-9a-zA-Z-]+>/lang/<code:[a-z]{2}>' => 'article/lang',
                'articles/<slug:[0-9a-zA-Z-]+>/references' => 'article/references',
            ],
        ],
    ],
    'params' => $params,
];
