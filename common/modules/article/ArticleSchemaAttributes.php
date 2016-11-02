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
        
        $this->attributes[] = new Attribute(
                'title', 
                array_combine($attributeSchema,['title','Title',1,1,1]), 
                [['label'=>'Title','type'=>'String']]
        );
        
        $this->attributes[] = new Attribute(
                'address', 
                array_combine($attributeSchema, ['address', 'Address', 0, 1, 1]), 
                [['label' => 'Line', 'type' => 'String']]
        );
        
        $this->attributes[] = new Attribute(
                'address_country', 
                array_combine($attributeSchema, ['address_country', 'Country', 0, 1, 1]), 
                [['label' => 'Country', 'type' => 'String']]
        );

        $this->attributes[] = new Attribute(
                'creation', 
                array_combine($attributeSchema,['creation','Creation',0,1,1]), 
                [['label'=>'Main Creation','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'keywords', 
                array_combine($attributeSchema,['keywords','Keywords',1,1,1]), 
                [['label'=>'Words','type'=>'String']]
        );
        
        $this->attributes[] = new Attribute(
                'text_class',
                array_combine($attributeSchema, ['text_class', 'Text Class', 1, 1, 1]), 
                [['label' => 'Code', 'type' => 'String']]
        );

        $this->attributes[] = new Attribute(
                'teaser', 
                array_combine($attributeSchema,['teaser','Teaser',1,1,1]), 
                [['label'=>'Teaser','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'abstract', 
                array_combine($attributeSchema,['abstract','Abstract',1,1,1]), 
                [['label'=>'Abstract','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'findings_positive', 
                array_combine($attributeSchema,['findings_positive','Findings Positive',0,1,1]), 
                [['label'=>'Item','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'findings_negative', 
                array_combine($attributeSchema,['findings_negative','Findings Negative',0,1,1]), 
                [['label'=>'Item','type'=>'Text']]
        );

        
        $this->attributes[] = new Attribute(
                'main_message', 
                array_combine($attributeSchema,['main_message','Main Message',1,1,1]), 
                [['label'=>'Text','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'main_text', 
                array_combine($attributeSchema,['main_text','Main Text',0,1,1]), 
                [['label'=>'Type','type'=>'String'], ['label'=>'Text','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'further_reading', 
                array_combine($attributeSchema, ['further_reading', 'Further Reading', 0, 1, 1]),
                [['label' => 'Title', 'type' => 'String'], ['label' => 'Full Citation', 'type' => 'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'further_reading', 
                array_combine($attributeSchema, ['further_reading', 'Further Reading', 0, 1, 1]),
                [['label' => 'Title', 'type' => 'String'], ['label' => 'Full Citation', 'type' => 'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'key_references', 
                array_combine($attributeSchema, ['key_references', 'Key References', 0, 1, 1]),
                [['label' => 'Title', 'type' => 'String'], ['label' => 'Reference', 'type' => 'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'term_groups', 
                array_combine($attributeSchema, ['term_groups', 'Term Groups', 0, 1, 1]),
                [['label' => 'Title', 'type' => 'String'], ['label' => 'Text', 'type' => 'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'ga_image', 
                array_combine($attributeSchema, ['ga_image', 'Ga Image', 1, 1, 1]), 
                [['label' => 'Title', 'type' => 'String'], ['label' => 'Path', 'type' => 'String']]
        );
        
        $this->attributes[] = new Attribute(
                'images', 
                array_combine($attributeSchema, ['images', 'Images', 1, 1, 1]), 
                [['label' => 'Title', 'type' => 'String'], ['label' => 'Path', 'type' => 'String']]
        );
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

