<?php
namespace common\components;

/*
 * Changed base request functionality
 */
class Request extends \yii\web\Request {
    public $web;
    public $adminUrl;
    
    /*
    * Init url rewrite path for not existing route
    */
    public function init() {
        parent::init();
        
        if ($this->getBaseUrl() !== $this->adminUrl) {
            \Yii::$container->get('Rewrite')->rewritePath($this);
        }
    }
    
    /*
     * remove frontend|backecnd in base path
     * @return string
    */
    public function getBaseUrl()
    {
        return str_replace($this->web, "", parent::getBaseUrl()) . $this->adminUrl;
    }

    protected function resolvePathInfo()
    {
        if($this->getUrl() === $this->adminUrl){
            return "";
        }else{
            return parent::resolvePathInfo();
        }
    }

}