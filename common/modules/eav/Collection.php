<?php
namespace common\modules\eav;

use common\modules\eav\contracts\EntityTypeInterface;
use common\modules\eav\contracts\EntityInterface;
use common\modules\eav\contracts\EntityModelInterface;

use common\modules\eav\collection\Attribute;
use common\modules\eav\collection\Value;
use common\modules\eav\collection\Entity;
use Exception;

class Collection {
    
    private $type;
    private $entity;
    private $selectAttribute = '*';
    
    protected $attributeCollection = null;
    protected $valueCollection = [];
    protected $entityCollection = null;
    protected $languages = null;
    public $isMulti;
    
    public function __construct(EntityTypeInterface $type, EntityInterface $entity) {
        
         $this->type = $type;
         $this->entity = $entity;
    }
    
    public function setAttributeFilter(array $selected) {
        $this->selectAttribute = $selected;
    }
    
    public function getEntity() {
        return $this->entityCollection;
    }
    
    public function getAttributes() {
        return $this->attributeCollection;
    }

    public function getLanguages() {
        return $this->languages;
    }
    
    public function initCollection($type, EntityModelInterface $model, $multiLang = false) {
        
        $attrFilter = $this->selectAttribute;
        
        $attributes = $this->type->find()
                                 ->where(['name' => $type])
                                 ->with(['eavTypeAttributes.eavAttribute' => function($query) use ($attrFilter) {
                                    
                                    if (is_array($attrFilter)) {
                                        return $query->where(['name' => $attrFilter]);
                                    }
                                    
                                    return $query;
                                 }])
                                 ->one();
                         
        if (!is_object($attributes)) {
            throw new Exception('Attributes did not set');
        }

        $entityModel = $this->entity->find()
                                    ->where([
                                        'type_id' => $attributes->id,
                                        'model_id' => $model->getId()
                                    ])
                                    ->one();
        
        if (!is_object($entityModel)) {
            throw new Exception('Entity did not set');
        }

        if ($multiLang) {
            $this->languages = $entityModel->getEavValueLanguage();
            $multiLang = (count($this->languages) > 1) ? true : false;
        }

        $this->isMulti = $multiLang;
                          
        unset($this->type);
        unset($this->entity);
        
        foreach ($attributes->eavTypeAttributes as $attrType) {

            if ($attrType->eavAttribute) {
                $this->attributeCollection[$attrType->eavAttribute->id] = new Attribute($attrType->eavAttribute->name, $attrType->eavAttribute->label);
            }
            
        }

        $valueQuery = $entityModel->getEavValues();
        
        if (!$multiLang) {
            $valueQuery->where(['lang_id' => 0]);
        }
        
        if (is_array($attrFilter)) {
            $valueQuery->andWhere(['attribute_id'=>array_keys($this->attributeCollection)]);
        }
        
        $valueRecords = $valueQuery->all();
        unset($valueQuery);
        
        foreach ($valueRecords as $value) {

            if (isset($this->attributeCollection[$value->attribute_id])) {
                
                $attribute = $this->attributeCollection[$value->attribute_id];

                if ($this->isMulti && isset($this->valueCollection[$attribute->getName()])) {

                    $v = $this->valueCollection[$attribute->getName()];
                    $v->addMultiData($value);
                    continue;
                }
                
                $v = new Value($attribute, $this->isMulti);
                
                if ($this->isMulti) {
                    $v->addMultiData($value);
                } else {
                    $v->addData($value);
                }
                
                $this->valueCollection[$attribute->getName()] = $v;
            }
        }

        $this->entityCollection = new Entity($this->valueCollection, $entityModel->name);
    }
}

