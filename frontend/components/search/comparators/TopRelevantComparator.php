<?php

namespace frontend\components\search\comparators;

use frontend\components\search\contracts\ComparatorInterface;

class TopRelevantComparator implements ComparatorInterface {

    private $types;
    private $phrase;

    public function __construct($search_phrase) {
        $this->phrase = $search_phrase;
    }

    public function sort(array $elements) {

        if (!count($elements)) {
            return $elements;
        }

        $this->cnt = count($elements);
        
        return $this->relivantOrderByPhrase($elements);
    }

    private function orderByType($elements) {
        
        foreach ($elements as $el) {

            $this->types[$el['type']][] = $el;
        }
    }

    private function relivantOrderByPhrase(array $items) {

        $top = array();
        $bottom = array();

        foreach ($items as $item) {

            if (preg_match("/($this->phrase)/i", $item['title'])) {

                $top[] = $item;
                continue;
            }

            $bottom[] = $item;
        }

        $this->orderByType($bottom);
        $relevant = $this->relivantOrder();

        return array_merge($top, $relevant);
    }

    private function relivantOrder() {

        $relevant = [];

        if (isset($this->types['article'])) {

            $relevant = array_slice($this->types['article'], 0, 4, true);
            
            foreach (array_keys($relevant) as $key) {
                unset($this->types['article'][$key]);
            }

            $this->cnt -= count($relevant);
        }

        if (isset($this->types['biography'])) {

            $relevantAuthor = array_slice($this->types['biography'], 0, 4, true);

            foreach (array_keys($relevantAuthor) as $key) {
                unset($this->types['biography'][$key]);
            }

            $this->cnt -= count($relevantAuthor);
            $relevant = array_merge($relevant, $relevantAuthor);
        }

        $i = 0;

        while ($i < $this->cnt) {

            $cnt = 0;
            
            foreach ($this->types as $type => $items) {
                $firtItem = current($items);
                if ($firtItem) {
                    $relevant[] = $firtItem;
                    $key = key($items);
                    unset($this->types[$type][$key]);
                    $cnt += 1;
                }
            }

            if (!$cnt) {
                break;
            }
            
            $i += $cnt;
        }

        return $relevant;
    }

}
