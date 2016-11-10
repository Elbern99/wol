<?php
namespace backend\modules\parser;

use backend\modules\parser\contracts\UploadInterface;

class ParserFacade {
    
    private $model;
    private $factory;
    
    public function __construct(UploadInterface $model) {
        $this->model = $model;
        $this->factory = \Yii::createObject(ParserFactory::class);
    }
    
    public function run() {

        $read = $this->factory->createReader()->read($this->model->getArchivePath());
        $result = $this->factory->createParser($this->model->getActionClass())->parse($read);
        
        return $result;
    }
     
}

