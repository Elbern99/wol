<?php
namespace common\modules\newsletter;

use common\modules\newsletter\contracts\NewsletterInterface;

class Newsletter {
    
    private $model;
    
    public function __construct(NewsletterInterface $model) {
        $this->model = $model;
    }
    
    public function getSubscriber(string $email) {
        
        $model = $this->model->getSubscriber($email);
        
        if (is_object($model) && $model instanceof NewsletterInterface) {
            $this->model = $model;
        }
    }
    
    public function setSubscriber(array $data) {
        return $this->model->setSubscriber($data);
    }
    
    
    public function getNewsletterAttributes():array {
        return $this->model->getAttributes();
    }
    
    public function getAttribute($name) {
        return $this->model->getAttribute($name) ?? null;
    }
    
}

