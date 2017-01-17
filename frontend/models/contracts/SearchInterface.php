<?php
namespace frontend\models\contracts;

interface SearchInterface {
    
    const ARTICLE_SEARCH_TYPE = 1;
    const BIOGRAPHY_SEARCH_TYPE = 2;
    const KEYTOPICS_SEARCH_TYPE = 3;
    const NEWS_SEARCH_TYPE = 4;
    const OPINIONS_SEARCH_TYPE = 5;
    const EVENTS_SEARCH_TYPE = 6;
    const VIDEOS_SEARCH_TYPE = 7;

    public function getHeadingFilter();
    public function getheadingModelFilter($id = null);
}