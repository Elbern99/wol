<?php
namespace frontend\components\search\filters;

use frontend\models\contracts\SearchInterface;

class TopSearchFilters extends AbstractSearchFilters implements SearchInterface {
    
    use \frontend\models\traits\SearchTrait;
    
    public function getData() {
        
        $filter = $this->filters['types']['filtered'];
        
        if (!$filter || !is_array($this->data)) {
            return [];
        }

        foreach($this->data as $key=>$data) {
            if ($this->filterByType($data['type'])) {
                unset($this->data[$key]);
            } elseif($data['type'] == 'key_topics' && $this->filterByTopic($data)) {
                unset($this->data[$key]);
            } elseif($data['type'] == 'authors' && $this->filterByAuthor($data)) {
                unset($this->data[$key]);
            }
        }

        return $this->data;
    }
    
    protected function filterByType($type) {

        $filter = $this->filters['types']['filtered'];
        $type = ($type == 'authors') ? 'biography' : $type;

        if (is_array($filter) && !in_array($this->getHeadingModelKey($type), $filter)) {
            return true;
        }
        
        return false;
    }
    
    protected function filterByTopic($topic) {
        
        $filter = $this->filters['topics']['filtered'];
        
        if (is_array($filter) && !in_array($topic['params']['id'], $filter)) {
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