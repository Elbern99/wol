<?php
namespace frontend\components\search\comparators;

use frontend\components\search\contracts\ComparatorInterface;

class DateComparator implements ComparatorInterface {
    
    private $types;
    private $order;
    
    public function __construct($order) {
        $this->order = $order;
    }
    
    public function sort(array $elements) {
        
        if (!count($elements) || $this->order == 'desc') {
            return $elements;
        }

        $this->orderByType($elements);
        
        return $this->dateOrder();
    }
    
    private function orderByType($elements) {
        
        foreach ($elements as $el) {
            if (isset($el['params']['created_at'])) {
                $this->types[$el['type']][$el['params']['created_at']] = $el;
            } else {
                $this->types[$el['type']][] = $el;
            }
        }
    }
    
    private function dateOrder() {

        $date = [];
        
        foreach ($this->types as $type => $params) {
            ksort($this->types[$type]);
            $date = array_merge($date, $this->types[$type]);
        }

        return $date;
    }
}
