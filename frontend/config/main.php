<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'eav_module', 'menu_module', 'settings_module', 'newsletter_module'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'class' => 'common\components\Request',
            'csrfParam' => '_csrf-frontend',
            'web'=> '/frontend/web'
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
                'page/<id:[0-9]+>' => 'page/index',
                'articles/' => 'article/index',
                'articles/<slug:[0-9a-z-]+>' => 'article/one-pager',
                'articles/<slug:[0-9a-z-]+>/long' => 'article/full',
                'articles/<slug:[0-9a-z-]+>/map' => 'article/map',
                'articles/<slug:[1-9a-z-]+>/lang/<code:[a-z]{2}>' => 'article/lang',
                'articles/<slug:[0-9a-z-]+>/references' => 'article/references',
                'category/<id:[0-9]+>' => 'category/index',
                'register' => 'site/signup',
                'reset' => 'site/request-password-reset',
                'my-account' => 'my-account/index',
                'subscribe' => 'site/subscribe',
                'events' => 'event/index',
                'events/<slug:[0-9a-z-]+>' => 'event/view',
                // 'key-topics' => 'topic/index',
                'opinions' => 'opinion/index',
                'opinions/<slug:[0-9a-z-]+>' => 'opinion/view',
                'find-an-expert' => 'authors/expert',
                //'key-topics' => 'topic/index',

            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ]
            ],
        ],
    ],
    'params' => $params,
];
