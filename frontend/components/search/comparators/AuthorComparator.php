<?php
namespace frontend\components\search\comparators;

use frontend\components\search\contracts\ComparatorInterface;

class AuthorComparator implements ComparatorInterface {
    
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
            $k = $key;
            if (isset($el['params']['authors'])) {
                if (is_array($el['params']['authors'])) {
                    $author = current($el['params']['authors']);
                    if (isset($author->surname)) {
                        $k = strtolower($author->surname).$key;
                    }
                } elseif (is_object($el['params']['authors'])) {
                    $author = $el['params']['authors'];
                    if (isset($author->surname)) {
                        $k = strtolower($author->surname).$key;
                    }
                } else {
                    $this->types[$el['type']][] = $el;
                    continue;
                }

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
                arsort($this->types[$type]);
            }
            $date = array_merge($date, $this->types[$type]);
        }

        return $date;
    }
}
