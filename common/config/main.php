<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'treemanager' => [
            'class' => '\common\modules\category\Module'
        ],
        'eav' => [
            'class' => '\common\modules\eav\Module',
            'components' => [
                'attribute' => '\common\models\eav\EavAttribute',
                'attribute_option' => '\common\models\eav\EavAttribute',
                'entity' => '\common\models\eav',
                'entity_type' => '',
                'value' => ''
            ]
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
                        'app/form' => 'form.php',
                        'app/text' => 'text.php',
                        'app/menu' => 'menu.php',
                    ],
                ],
            ],
        ],
    ],
];
