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
        $this->attributes[] = new Attribute('teaser', array_combine($attributeSchema,['teaser','Teaser',0,1,1]), [['label'=>'Teaser','type'=>'Text']]);
        $this->attributes[] = new Attribute('abstract', array_combine($attributeSchema,['abstract','Abstract',0,1,1]), [['label'=>'Abstract','type'=>'Text']]);
        $this->attributes[] = new Attribute('findings_positive', array_combine($attributeSchema,['findings_positive','Findings Positive',0,1,1]), [['label'=>'Item','type'=>'Text']]);
        $this->attributes[] = new Attribute('findings_negative', array_combine($attributeSchema,['findings_negative','Findings Negative',0,1,1]), [['label'=>'Item','type'=>'Text']]);
        $this->attributes[] = new Attribute('main_message', array_combine($attributeSchema,['main_message','Main Message',0,1,1]), [['label'=>'Text','type'=>'Text']]);
        $this->attributes[] = new Attribute('main_part', array_combine($attributeSchema,['main_part','Main Part',0,1,1]), [['label'=>'Type','type'=>'String'], ['label'=>'Type','type'=>'String']]);
        
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

