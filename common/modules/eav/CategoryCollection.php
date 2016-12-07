<?php
namespace common\modules\eav;

use common\modules\eav\contracts\EntityInterface;
use Yii;

class CategoryCollection {
    private $entity;
    private $selectAttribute = [];
    private $values = null;
    
    public function __construct(EntityInterface $entity) {
        $this->entity = $entity;
    }
    
    public function setAttributeFilter(array $selected) {
        $this->selectAttribute = array_merge($selected, $this->selectAttribute);
    }
    
    public function initCollection($type, $ids) {
        
        $models = Yii::$app->modules['eav_module']->components;
        
        $this->values = $this->entity->find()
                        ->alias('e')
                        ->select(['`v`.`id`', 'model_id'=>'`e`.`model_id`', '`ea`.`name`', '`v`.`value`'])
                        ->innerJoin(['t' => $models['type']::tableName()], '`e`.`type_id` = `t`.`id`')
                        ->innerJoin(['ea' => $models['attribute']::tableName()])
                        ->leftJoin(['v' => $models['value']::tableName()], 
                                '`v`.`entity_id` = `e`.`id` and `v`.`attribute_id` = `ea`.`id`'
                        )->where([
                            '`e`.`model_id`' => $ids,
                            '`t`.`name`' => $type,
                            '`ea`.`name`' => $this->selectAttribute,
                            '`v`.`lang_id`' => 0
                        ])
                        ->asArray()
                        ->all();
    }
    
    private function getFormatedArray() {
        
        $formated = [];

        foreach ($this->values as $value) {
            
            if (isset($formated[$value['model_id']])) {
                
                $formated[$value['model_id']][$value['name']] = $value['value'];
                continue;
            }
            
            $formated[$value['model_id']] = array($value['name'] => $value['value']);
        }
        
        return $formated;
    }
    
    public function getValues($formated = true) {
        
        if ($formated) {
            return $this->getFormatedArray();
        }
        
        return $this->values;
    }
}