<?php
namespace frontend\components\search\comparators;

use frontend\components\search\contracts\ComparatorInterface;

class RelevantComparator implements ComparatorInterface {
    
    private $types;
    private $results;

    /**
     * @var array list of search types. It is order output on search page.
     */
    private $orderOutput = [
        'article',
        'biography',
        'key_topics',
        'news',
        'opinions',
        'events',
        'videos',
        'papers',
        'policypapers'
    ];
    
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
        foreach ($this->orderOutput as $type) {
            if (isset($this->types[$type]) && $this->types[$type]) {
                foreach ($this->types[$type] as $element) {
                    $relevant[] = $element;
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
