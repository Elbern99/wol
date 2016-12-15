<?php
namespace frontend\models\traits;

trait SearchTrait {

    protected $headingModel = [
        self::ARTICLE_SEARCH_TYPE => 'article',
    ];
    
    protected $headingLabel = [
        self::ARTICLE_SEARCH_TYPE => 'Article',
        self::BIOGRAPHY_SEARCH_TYPE => 'Biography'
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
}
