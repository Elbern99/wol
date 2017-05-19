<?php
namespace frontend\components\search\filters;

abstract class AbstractSearchFilters {
    
    protected $filters;
    protected $data;
    
    public function __construct($filters, $data) {
        $this->filters = $filters;
        $this->data = $data;
    }
    
    abstract public function getData();
}

