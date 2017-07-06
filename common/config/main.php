<?php
return [
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
    'components' => [
        'cacheFrontend' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
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
