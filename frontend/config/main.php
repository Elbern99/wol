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
                'home' => 'site/index',
                'page/<id:[0-9]+>' => 'page/index',
                'articles/' => 'article/index',
                'articles/<slug:[0-9a-zA-Z-]+>' => 'article/one-pager',
                'articles/<slug:[0-9a-zA-Z-]+>/long' => 'article/full',
                'articles/<slug:[0-9a-zA-Z-]+>/map' => 'article/map',
                'articles/<slug:[1-9a-zA-Z-]+>/lang/<code:[a-z]{2}>' => 'article/lang',
                'articles/<slug:[0-9a-zA-Z-]+>/references' => 'article/references',
                'category/<id:[0-9]+>' => 'category/index',
                'register' => 'site/signup',
                'reset' => 'site/request-password-reset',
                'my-account' => 'my-account/index',
                'subscribe' => 'site/subscribe',
                'events' => 'event/index',
                'events/<slug:[0-9a-z-]+>' => 'event/view',
                'opinions' => 'opinion/index',
                'opinions/<slug:[0-9a-z-]+>' => 'opinion/view',
                'videos' => 'video/index',
                'videos/<slug:[0-9a-z-]+>' => 'video/view',
                'news' => 'news/index',
                'news/<slug:[0-9a-z-]+>' => 'news/view',
                'news/newsletters/<year:[0-9]+>/<month:[0-9]+>' => 'news/newsletters',
                'find-an-expert' => 'authors/expert',
                'authors' => 'authors/index',
                'authors/<url_key:[0-9a-z-]+>' => 'authors/profile',
                'key-topics' => 'topic/index',
                'key-topics/<slug:[0-9a-z-]+>' => 'topic/view',
                'editorial-board' => 'authors/editorial'
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
