<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'wol@iza.org',
    'user.passwordResetTokenExpire' => 3600,
    'articleModelDetail' => [
        'language' => '\common\models\Lang',
        'article_category' => '\common\models\ArticleCategory',
        'article_author' => '\common\models\ArticleAuthor',
        'source' => '\common\models\DataSource',
    ],
    'authorModelDetail' => [
        'author_roles' => '\common\models\AuthorRoles',
        'article_category' => '\common\models\AuthorCategory'
    ],
    'cms_page_modules' => [
        'accordion' => '\common\models\CmsPageSections',
        'simple' => '\common\models\CmsPagesSimple',
        'widget' => 'common\models\CmsPagesWidget'
    ],
    'category_type_class' => [
        'article' => '\frontend\models\category\ArticleRepository'
    ]
];
