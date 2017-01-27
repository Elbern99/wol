<?php
return [
    'adminEmail' => 'admin@example.com',
    'default_lang' => [
        'id' => 0,
        'name' => 'English',
        'image' => '/images/lang/english.jpg'
    ],
    'search' => [
        'article' => '\common\models\ArticleSearch',
        'biography' => '\common\models\BiographySearch',
        'news' => '\common\models\NewsSearch',
        'key_topics' => '\common\models\TopicsSearch',
        'opinions' => '\common\models\OpinionsSearch',
        'events' => '\common\models\EventsSearch',
        'videos' => '\common\models\VideosSearch',
    ],
    'article_limit' => 10,
    'opinion_limit' => 4,
    'video_limit' => 4,
    'news_limit' => 7,
    'topic_limit' => 12,
    'opinion_sidebar_limit' => 5,
    'video_sidebar_limit' => 5,
    'latest_articles_sidebar_limit' => 5,
    'latest_news_sidebar_limit' => 5,
    'expert_limit' => 5,
    'search_result_limit' => 50,
    'authors_limit' => 10,
    'topic_articles_limit' => 6,
    'topic_videos_limit' => 6,
    'topic_opinions_limit' => 6,
    'topic_events_limit' => 6,
    'authors_limit' => 10,
    'home_article_limit' => 4,
    'home_event_limit' => 3,
    'home_news_limit' => 4,
    'page_widget' => [
        'editorial_board' => [
            'views_on_iza', 'socials', 
            'stay_up_to_date', 'iza_journals',
            'editorial_board_widget'
        ],
        'profile' => ['ask_the_expert']
    ]
];
