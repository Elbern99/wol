<?php
namespace common\modules\eav;

use common\modules\eav\contracts\EntityTypeInterface;
use common\modules\eav\contracts\EntityInterface;
use common\modules\eav\contracts\EntityModelInterface;

use common\modules\eav\collection\Attribute;
use common\modules\eav\collection\Value;
use common\modules\eav\collection\Entity;

class Collection {
    
    private $type;
    private $entity;
    
    protected $attributeCollection = null;
    protected $valueCollection = [];
    protected $entityCollection = null;
    
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


    public function initCollection($type, EntityModelInterface $model, $lang = 'all') {

        $attributes = $this->type->find()
                ->where(['name' => $type])
                ->with('eavTypeAttributes.eavAttribute')
                ->one();
        
        $entityModel = $this->entity->find()
                        ->where(['type_id' => $attributes->id,'model_id' => $model->getId()])
                        ->with('eavValues')
                        ->one();

        unset($this->type);
        unset($this->entity);
        
        foreach ($attributes->eavTypeAttributes as $attrType) {

            $related = $attrType->getRelatedRecords();

            foreach ($related as $attribute) {
                $this->attributeCollection[$attribute->id] = new Attribute($attribute->name, $attribute->label);
            }
            
        }
        
        $valueRecords = $entityModel->getRelatedRecords();
        
        foreach ($valueRecords['eavValues'] as $value) {

            if (isset($this->attributeCollection[$value->attribute_id])) {
                
                $attribute = $this->attributeCollection[$value->attribute_id];
                
                if (isset($this->valueCollection[$attribute->getName()])) {
                    
                    $v = $this->valueCollection[$attribute->getName()];
                    $v->addData($value->value);
                    continue;
                }
                
                $v = new Value($attribute);
                $v->addData($value->value);
                $this->valueCollection[$attribute->getName()] = $v;
            }
        }

        $this->entityCollection = new Entity($this->valueCollection, $entityModel->name);
    }
}

