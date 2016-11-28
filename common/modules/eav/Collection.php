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
    
    protected $attributeCollection = null;
    protected $valueCollection = [];
    protected $entityCollection = null;
    protected $lanuages = null;
    public $isMulti;
    
    public function __construct(EntityTypeInterface $type, EntityInterface $entity) {
        
         $this->type = $type;
         $this->entity = $entity;
    }
    
    public function getEntity() {
        return $this->entityCollection;
    }
    
    public function getAttributes() {
        return $this->attributeCollection;
    }

    public function getLanguages() {
        return $this->lanuages;
    }
    
    public function initCollection($type, EntityModelInterface $model, $multiLang = true) {
        
        $attributes = $this->type->find()
                                 ->where(['name' => $type])
                                 ->with('eavTypeAttributes.eavAttribute')
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

            $related = $attrType->getRelatedRecords();

            foreach ($related as $attribute) {
                $this->attributeCollection[$attribute->id] = new Attribute($attribute->name, $attribute->label);
            }
            
        }
        
        if ($multiLang) {
            $valueRecords = $entityModel->getEavValues()->all();
        } else {
            $valueRecords = $entityModel->getEavValues()->where(['lang_id' => 0])->all();
        }

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

