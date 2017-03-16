<?php
namespace frontend\components\search\comparators;

use frontend\components\search\contracts\ComparatorInterface;

class RelevantComparator implements ComparatorInterface {
    
    private $types;
    private $results;
    
    public function __construct($results) {
        $this->results = $results;
    }
    
    public function sort(array $elements) {
        
        if (!count($elements)) {
            return $elements;
        }

        $this->orderByType($elements);
        return $this->relivantOrder();
    }
    
    private function orderByType($elements) {
        
        foreach ($elements as $el) {
            $this->types[$el['type']][] = $el;
        }
    }

    private function relivantOrder() {

        $relevant = []; 

        foreach ($this->results as $result) {
            
            if (isset($this->types[$result['type']])) {
                
                $items = $this->types[$result['type']];
                $item = $this->searchItemByID($result['id'], $items);
                
                if ($item) {
                    $relevant[] = $item;
                }
            }
        }
        
        return $relevant;
    }
    
    private function searchItemByID($id, $items) {
        
        foreach ($items as $item) {
            if (isset($item['params']['id']) && $item['params']['id'] == $id) {
                return $item;
            }
        }
        
        return null;
    }
}
