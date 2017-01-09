<?php
return [
    'adminEmail' => 'admin@example.com',
    'default_lang' => [
        'id' => 0,
        'name' => 'English',
        'image' => '/images/lang/english.jpg'
    ],
    'search' => [
        'article' => '\common\models\ArticleSearch'
    ],
    'article_limit' => 10,
    'opinion_limit' => 4,
    'video_limit' => 4,
    'news_limit' => 4,
    'opinion_sidebar_limit' => 5,
    'video_sidebar_limit' => 5,
    'latest_articles_sidebar_limit' => 5,
    'latest_news_sidebar_limit' => 5,
    'expert_limit' => 5,
    'search_result_limit' => 50,
    'authors_limit' => 10,
    'page_widget' => [
        'editorial_board' => [
            'views_on_iza', 'socials', 
            'stay_up_to_date', 'iza_journals',
            'editorial_board_widget'
        ],
        'profile' => ['ask_the_expert']
    ]
];
