<?php
namespace frontend\models;

class Page {
    
    private $cms;
    private $widgets;
    private $page;
    
    public function __construct(array $cms, $widgets = [], $page = []) {
        
        $this->cms = $cms;
        $this->widgets = $widgets;
        $this->page = $page;
    }
    
    public function __call($name, $argument) {
        
        $storage = null;
        
        switch (strtolower($name)) {
            
            case 'cms':
                $storage = $this->cms;
                break;
            case 'widget':
                $storage = $this->widgets;
                break;
            case 'page':
                $storage = $this->page;
                break;
        }
        
        if (isset($storage[$argument[0]])) {
            return $storage[$argument[0]];
        }
        
        return null;
    }
    
    public function getPage() {
        return $this->page;
    }
    
    public function getWidgets() {
        return $this->widgets;
    }
}

