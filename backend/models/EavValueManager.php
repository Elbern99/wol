<?php
namespace backend\models;

use common\modules\eav\contracts\ValueInterface;
use common\models\eav\EavValue;
use Yii;

class EavValueManager {
    
    private $model;
    private $oldAttribute;
    
    public function __construct(ValueInterface $model, array $values) {
        
        $this->model = $model;
        $this->oldAttribute = $values;
    }
    
    public function save() {
        
        $values = $this->model->value;

        foreach ($values as $key => $value) {

            if ($this->isDirty($value)) {
                
                $newValue = $this->valueStringFormat($value);

                if (is_string($newValue)) {

                    $this->update($newValue, $key);
                }
            }
        }
    }
    
    private function update(string $value, int $id) {
        return Yii::$app->db->createCommand()
                    ->update(EavValue::tableName(), ['value' => $value], ['id' => $id])
                    ->execute();
    }
    
    private function valueStringFormat($value) {
        
        foreach ($value as $attribute => $v) {

            $data = $this->oldAttribute[$attribute]->getData();

            if ($this->oldAttribute[$attribute]->isMulti()) {
                $oldData = $data[$v['lang']];
                unset($v['lang']);
                
                if (is_array($oldData)) {
                    
                    $mass = [];

                    foreach ($oldData as $k=>$old) {
                        
                        $properties = array_keys(get_object_vars($old));
                        $newData = new \stdClass();

                        foreach ($properties as $property) {
                            
                            if (!isset($v[$k][$property])) {
                                $newData->$property = $old->$property;
                                continue;
                            }
                            
                            $newData->$property = $v[$k][$property];
                        }

                        $mass[$k] = $newData;
                    }
                    
                    return serialize($mass);
                }
                
                $properties = array_keys(get_object_vars($oldData));
                
                
                $newData = new \stdClass();
                
                foreach ($properties as $property) {
                    $newData->$property = $v[$property];
                }

                return serialize($newData);
            }

        }
        
        return false;
    }
    
    private function isDirty($value) {
        
        foreach ($value as $attribute => $v) {
            
            $data = $this->oldAttribute[$attribute]->getData();
            
            if ($this->oldAttribute[$attribute]->isMulti()) {
                
                $oldData = $data[$v['lang']];
                unset($v['lang']);
                
                if (is_array($oldData)) {
                    
                    $result = false;
                    
                    foreach ($oldData as $k=>$old) {
                        
                        $properties = get_object_vars($old);

                        if ($this->compareArrayData($v[$k], $properties)) {
                            $result = true;
                            break;
                        }
                    }
                    

                    return $result;
                }
                
                $properties = get_object_vars($oldData);

                if ($this->compareArrayData($v, $properties)) {
                    return true;
                }
                
            } else {
                var_dump($data);exit;
            }
        }
        
        return false;
    }
    
    private function compareArrayData($current, $properties) {

        $result = false;
                
        foreach ($properties as $i => $property) {
            
            if (!isset($current[$i])) {
                continue;
            }
            
            if (is_array($current[$i])) {

                if ($this->compareArrayData($current[$i], $property)) {
                    $result = true;
                    break;
                }
            } else {

                if (strcmp($current[$i], $property)) {
                    $result = true;
                    break;
                }
            }

        }
        
        return $result;
    }
}

