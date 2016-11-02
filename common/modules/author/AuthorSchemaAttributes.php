<?php
namespace common\modules\author;

use common\modules\eav\contracts\AttributeInterface;
use common\modules\eav\Attribute;

class AuthorSchemaAttributes {
    
    private $attributes;
    private $modelAtribute = null;

    
    public function  __construct(AttributeInterface $model) {
        $this->modelAtribute = $model;
        
        $this->instanceAttribute();
    }
    
    private function instanceAttribute() {
        
        $attributeSchema = $this->modelAtribute->getAttributeSchema();
        
        $this->attributes[] = new Attribute(
                'name', 
                array_combine($attributeSchema,['name','Name',1,1,1]), 
                [['label'=>'First Name','type'=>'String'], ['label'=>'Middle Name','type'=>'String'], ['label'=>'Last Name','type'=>'String']]
        );
        
        $this->attributes[] = new Attribute(
                'author_country', 
                array_combine($attributeSchema,['creation','Creation',1,1,1]), 
                [['label'=>'Code','type'=>'String']]
        );
        
        $this->attributes[] = new Attribute(
                'author_testimonial', 
                array_combine($attributeSchema,['testimonial','Testimonial',1,1,1]), 
                [['label'=>'Testimonial','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'author_publications', 
                array_combine($attributeSchema,['publications','Publications',1,1,1]), 
                [['label'=>'Publications','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'author_affiliation', 
                array_combine($attributeSchema,['affiliation','Affiliation',1,1,1]), 
                [['label'=>'Affiliation','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'author_position',
                array_combine($attributeSchema,['position','Position',1,1,1]), 
                [['label'=>'Current','type'=>'Text'], ['label'=>'Past','type'=>'Text'], ['label'=>'Advisory','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'degree', 
                array_combine($attributeSchema,['degree','Degree',1,1,1]), 
                [['label'=>'Degree','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'interests', 
                array_combine($attributeSchema,['interests','Interests',1,1,1]), 
                [['label'=>'Interests','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'expertise', 
                array_combine($attributeSchema,['expertise','Expertise',1,1,1]), 
                [['label'=>'Expertise','type'=>'Text']]
        );
        
        $this->attributes[] = new Attribute(
                'expertise_type', 
                array_combine($attributeSchema,['expertise_type','Expertise Type',0,1,1]), 
                [['label'=>'Expertise Type','type'=>'String']]
        );
        
        $this->attributes[] = new Attribute(
                'expertise_url', 
                array_combine($attributeSchema,['expertise_url','Expertise Url',0,1,1]), 
                [['label'=>'Url','type'=>'String'], ['label'=>'Text','type'=>'String']]
        );
        
        $this->attributes[] = new Attribute(
                'author_language', 
                array_combine($attributeSchema,['language','Language',0,1,1]), 
                [['label'=>'Code','type'=>'String'], ['label'=>'Proficiency','type'=>'String']]
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

