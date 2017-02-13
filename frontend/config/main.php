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
                'find-a-topic-spokesperson' => 'authors/expert',
                'authors' => 'authors/index',
                'authors/letter' => 'authors/letter',
                'authors/<url_key:[0-9a-z-]+>' => 'authors/profile',
                'editors/<url_key:[0-9a-z-]+>' => 'authors/editor-profile',
                'spokespeople/<url_key:[0-9a-z-]+>' => 'authors/expert-profile',
                'key-topics' => 'topic/index',
                'key-topics/<slug:[0-9a-z-]+>' => 'topic/view',
                'editorial-board' => 'authors/editorial',
                'data-sources' => 'site/sources',
                'unsubscribe' => 'site/unsubscribe',
                'press-releases' => 'press-releases/index',
                'press-releases/<slug:[0-9a-z-]+>' => 'press-releases/view',
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
