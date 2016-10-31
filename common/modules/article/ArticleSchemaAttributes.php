<?php
namespace common\modules\article;

use common\modules\eav\contracts\AttributeInterface;
use common\modules\eav\Attribute;

class ArticleSchemaAttributes {
    
    private $attributes;
    private $modelAtribute = null;

    
    public function  __construct(AttributeInterface $model) {
        $this->modelAtribute = $model;
        
        $this->instanceAttribute();
    }
    
    private function instanceAttribute() {
        
        $attributeSchema = $this->modelAtribute->getAttributeSchema();
        
        $this->attributes[] = new Attribute('title', array_combine($attributeSchema,['title','Title',0,1,1]), [['label'=>'Title','type'=>'String']]);
        $this->attributes[] = new Attribute('creation', array_combine($attributeSchema,['creation','Creation',0,1,1]), [['label'=>'Main Creation','type'=>'Text']]);
        
    }
    
    public function addAttribute($name, $params, $options) {
        
        $attributeSchema = $this->modelAtribute->getAttributeSchema();
        $attribute = new Attribute($name, array_combine($attributeSchema, $params), $options);
        array_push($this->attributes,$attribute);
    }
    
    public function getAttributes() {
        return $this->attributes;
    } 
}

