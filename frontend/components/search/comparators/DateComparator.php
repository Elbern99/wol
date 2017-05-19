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
        
        if (!count($elements)) {
            return $elements;
        }

        $this->orderByType($elements);
        
        if ($this->order == 'desc') {
            return $this->dateOrder();
        }
        
        return $this->dateOrderAsc();
    }
    
    private function orderByType($elements) {

        foreach ($elements as $k => $el) {
            if (isset($el['params']['created_at'])) {
                
                $key = $el['params']['created_at'];
                
                if (isset($this->types[$el['type']]) && array_key_exists($key,  $this->types[$el['type']])) {
                    $key += (int)$k;
                }
                
                $this->types[$el['type']][$key] = $el;
                
            } else {
                $this->types[$el['type']][] = $el;
            }
        }       
        ksort($this->types);
    }
    
    private function dateOrder() {

        $date = [];
        
        foreach ($this->types as $type => $params) {
            krsort($this->types[$type]);
            $date = array_merge($date, $this->types[$type]);
        }

        return $date;
    }
    
    private function dateOrderAsc() {

        $date = [];
        
        foreach ($this->types as $type => $params) {
            ksort($this->types[$type]);
            $date = array_merge($date, $this->types[$type]);
        }

        return $date;
    }
}
