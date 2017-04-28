<?php
namespace frontend\components\search\filters;

use frontend\models\contracts\SearchInterface;

class MainSearchFilters extends AbstractSearchFilters implements SearchInterface {
    
    use \frontend\models\traits\SearchTrait;
    
    public function getData() {
        
        $filter = $this->filters['types']['filtered'];
        
        if (!$filter) {
            return [];
        }
        
        foreach($this->data as $key=>$data) {
            if ($this->filterByType($data['type'])) {
                unset($this->data[$key]);
            } elseif($data['type'] == 'article' && $this->filterByArticle($data)) {
                unset($this->data[$key]);
            } elseif($data['type'] == 'biography' && $this->filterByAuthor($data)) {
                unset($this->data[$key]);
            }
        }

        return $this->data;
    }
    
    protected function filterByType($type) {

        $filter = $this->filters['types']['filtered'];

        if (!in_array($this->getHeadingModelKey($type), $filter)) {
            return true;
        }
        
        return false;
    }
    
    protected function filterByArticle($article) {
        
        $filter = $this->filters['category']['filtered'];
        
        if (is_array($filter)) {
            
            $categories = $article['params']['categories'];
            
            foreach($categories as $id) {
                
                if (in_array($id, $filter)) {
                    return false;
                }
            }
            
            return true;

        }
        
        return false;
        
    }
    
    protected function filterByAuthor($author) {
        
        $filter = $this->filters['biography']['filtered'];

        if (is_array($filter) && !in_array($author['params']['id'], $filter)) {
            return true;
        }
        
        return false;
    }
}