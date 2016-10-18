<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'treemanager' => [
            'class' => '\common\components\category\Module',
            'params' => [
                'typeClass' => '\common\components\CategoryType',
                'categoryTypes' => [
                    1 => 'Video',
                    2 => 'Article'
                ]
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
