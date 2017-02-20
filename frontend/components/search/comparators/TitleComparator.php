<?php
namespace frontend\components\search\comparators;

use frontend\components\search\contracts\ComparatorInterface;

class TitleComparator implements ComparatorInterface {
    
    private $types;
    private $order;
    
    public function __construct($order) {
        $this->order = $order;
    }
    
    public function sort(array $elements) {
        
        if (!count($elements)) {
            return $elements;
        }

        $this->orderByType($elements);
        
        return $this->dateOrder();
    }
    
    private function orderByType($elements) {
        
        foreach ($elements as $key=>$el) {
            if (isset($el['params']['title'])) {
                $k = strtolower(strstr($el['params']['title'], ' ', true)).$key;
                $this->types[$el['type']][$k] = $el;
            } else {
                $this->types[$el['type']][] = $el;
            }
        }
    }
    
    private function dateOrder() {

        $date = [];
        
        foreach ($this->types as $type => $params) {
            if ($this->order == 'asc') {
                ksort($this->types[$type]);
            } else {
                krsort($this->types[$type]);
            }
            $date = array_merge($date, $this->types[$type]);
        }

        return $date;
    }
}
