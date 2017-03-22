<?php
namespace frontend\components\search\comparators;

use frontend\components\search\contracts\ComparatorInterface;

class RelevantComparator implements ComparatorInterface {
    
    private $types;
    private $cnt = 0;
    private $phrase;
    
    public function __construct($searchPhrase = null) {
        $this->phrase = $searchPhrase;
    }
    
    public function sort(array $elements) {
        
        if (!count($elements)) {
            return $elements;
        }
        
        $this->cnt = count($elements);

        if ($this->phrase) {
            return $this->relivantOrderByPhrase($elements);
        }
        
        $this->orderByType($elements);
        return $this->relivantOrder();
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
            
            if ($item['type'] == 'article') {
                
                if(preg_match("/($this->phrase)/i", $item['params']['title'])) {
                    $top[] = $item;
                    continue;
                }
                
                if (is_array($item['params']['authors'])) {
                    
                    foreach ($item['params']['authors'] as $author) {

                        $serched = str_replace(' ', '|',  $this->phrase);

                        if (preg_match("/($serched)/i", $author->name)) {

                            $top[] = $item;
                            break;
                        }
                    }
                }
                
                $bottom[] = $item;
                continue;
            }
            
            if ($item['type'] == 'biography') {
                
                if(preg_match("/($this->phrase)/i", $item['params']['name'])) {
                    $top[] = $item;
                    continue;
                }
                
                $bottom[] = $item;
                continue;
            }
            
            if(preg_match("/($this->phrase)/i", $item['params']['title'])) {
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
            
            if (isset($this->types['article']) && count($this->types['article'])) {
                $firtItem = current($this->types['article']);
                $relevant[] = $firtItem;
                $key = key($this->types['article']);
                unset($this->types['article'][$key]);
                $cnt += 1;
            }
            
            if (isset($this->types['biography']) && count($this->types['biography'])) {
                $firtItem = current($this->types['biography']);
                $relevant[] = $firtItem;
                $key = key($this->types['biography']);
                unset($this->types['biography'][$key]);
                $cnt += 1;
            }
            
            if (isset($this->types['events']) && count($this->types['events'])) {
                $firtItem = current($this->types['events']);
                $relevant[] = $firtItem;
                $key = key($this->types['events']);
                unset($this->types['events'][$key]);
                $cnt += 1;
            }
            
            if (isset($this->types['news']) && count($this->types['news'])) {
                $firtItem = current($this->types['news']);
                $relevant[] = $firtItem;
                $key = key($this->types['news']);
                unset($this->types['news'][$key]);
                $cnt += 1;
            }
            
            if (isset($this->types['opinions']) && count($this->types['opinions'])) {
                $firtItem = current($this->types['opinions']);
                $relevant[] = $firtItem;
                $key = key($this->types['opinions']);
                unset($this->types['opinions'][$key]);
                $cnt += 1;
            }
            
            if (isset($this->types['videos']) && count($this->types['videos'])) {
                $firtItem = current($this->types['videos']);
                $relevant[] = $firtItem;
                $key = key($this->types['videos']);
                unset($this->types['videos'][$key]);
                $cnt += 1;
            }
            
            if (!$cnt) {
                break;
            }
            
            $i += $cnt;
        }
        
        return $relevant;
    }
}
