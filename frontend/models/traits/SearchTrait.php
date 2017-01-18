<?php
namespace frontend\models\traits;

trait SearchTrait {

    protected $headingModel = [
        self::ARTICLE_SEARCH_TYPE => 'article',
        self::BIOGRAPHY_SEARCH_TYPE => 'biography',
        /*self::KEYTOPICS_SEARCH_TYPE => 'key_topics',
        self::NEWS_SEARCH_TYPE => 'news',
        self::OPINIONS_SEARCH_TYPE => 'opinions',
        self::EVENTS_SEARCH_TYPE => 'events',
        self::VIDEOS_SEARCH_TYPE => 'videos',*/
    ];
    
    protected $headingLabel = [
        self::ARTICLE_SEARCH_TYPE => 'Article',
        self::BIOGRAPHY_SEARCH_TYPE => 'Biography',
        /*self::KEYTOPICS_SEARCH_TYPE => 'Key Topics',
        self::NEWS_SEARCH_TYPE => 'News',
        self::OPINIONS_SEARCH_TYPE => 'Opinions',
        self::EVENTS_SEARCH_TYPE => 'Events',
        self::VIDEOS_SEARCH_TYPE => 'Videos',*/
    ];
    
    public function getHeadingFilter() {
        return $this->headingLabel;
    }
    
    public function getheadingModelFilter($id = null) {
        
        if ($id) {
            return $this->headingModel[$id] ?? null;
        }
        
        return $this->headingModel;
    }
    
    public function getHeadingModelKey($value = null) {
        
        if ($key = array_search($value, $this->headingModel)) {
            return $key;
        }
        
        return false;
    }
    
    public function getTypeIds(): array {
        
        return [
            self::ARTICLE_SEARCH_TYPE,
            self::BIOGRAPHY_SEARCH_TYPE,
            /*self::KEYTOPICS_SEARCH_TYPE
            self::NEWS_SEARCH_TYPE
            self::OPINIONS_SEARCH_TYPE
            self::EVENTS_SEARCH_TYPE
            self::VIDEOS_SEARCH_TYPE*/
        ];
    }
}
