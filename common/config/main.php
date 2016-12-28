<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
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
        'settings_module' => [
            'class' => '\common\modules\settings\Module',
        ],
        'newsletter_module' => [
            'class' => '\common\modules\newsletter\Module',
            'components' => [
                'newsletter_model' => '\common\models\Newsletter',
                'newsletter_facade' => '\common\modules\newsletter\Newsletter'
            ]
        ],
        'task' => [
            'class' => 'common\modules\task\Module',
        ]
    ],
    'components' => [
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
                    ],
                ],
            ],
        ],
        'queue' => [
            'class' => 'UrbanIndo\Yii2\Queue\Queues\DbQueue',
            'db' => 'db',
            'tableName' => 'queue',
            'module' => 'task',
        ],
    ],
    'controllerMap' => [
        'queue' => [
            'class' => 'UrbanIndo\Yii2\Queue\Console\Controller',
        ],
    ],
];
