<?php
return [
    'name' => 'IZA World of Labor',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'eav_module' => [
            'class' => '\common\modules\eav\Module',
            'components' => [
                'attribute' => '\common\models\eav\EavAttribute',
                'attribute_option' => '\common\models\eav\EavAttributeOption',
                'entity' => '\common\models\eav\EavEntity',
                'type' => '\common\models\eav\EavType',
                'atribute_type' => '\common\models\eav\EavTypeAttributes',
                'value' => '\common\models\eav\EavValue'
            ]
        ],
        'task' => [
            'class' => 'common\modules\task\Module',
        ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cacheFrontend' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
        ],
        'frontendUrlManager' => [
            'class' => \yii\web\UrlManager::className(),
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'scriptUrl' => '',
            'baseUrl' => 'https://wol.iza.org',
            'hostInfo' => 'https://wol.iza.org',
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
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'forceTranslation' => true,
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/../messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/exception' => 'exception.php',
                        'app/form' => 'form.php',
                        'app/text' => 'text.php',
                        'app/menu' => 'menu.php',
                        'app/messages' => 'messages.php',
                        'app/article' => 'article.php'
                    ],
                ],
            ],
        ],
        'queue' => [
            'class' => '\UrbanIndo\Yii2\Queue\Queues\RedisQueue',
            'key' => 'queue',
            'module' => 'task',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
    ],
    'controllerMap' => [
        'queue' => [
            'class' => 'UrbanIndo\Yii2\Queue\Console\Controller',
        ],
    ],
];
