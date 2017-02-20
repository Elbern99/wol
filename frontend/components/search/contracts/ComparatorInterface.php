<?php
namespace frontend\components\search\contracts;

interface ComparatorInterface {
    
    public function sort(array $elements);
}
