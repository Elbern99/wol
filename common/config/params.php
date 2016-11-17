<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'articleModelDetail' => [
        'language' => '\common\models\Lang',
        'article_category' => '\common\models\ArticleCategory',
        'article_author' => '\common\models\ArticleAuthor'
    ],
    'authorModelDetail' => [
        'author_roles' => '\common\models\AuthorRoles',
        'article_category' => '\common\models\AuthorCategory'
    ]
];
