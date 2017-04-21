<?php
namespace backend\models;

use common\modules\eav\contracts\ValueInterface;
use common\models\eav\EavValue;
use Yii;

class EavValueManager {
    
    private $model;
    private $oldAttribute;
    private $triggers;
    public $errors = [];
    
    public function __construct(ValueInterface $model, array $values, $triggers = []) {
        
        $this->model = $model;
        $this->oldAttribute = $values;
        $this->triggers = $triggers;
    }
    
    public function save() {
        
        $values = $this->model->value;

        foreach ($values as $key => $value) {

            if ($this->isDirty($value)) {
                
                $newValue = $this->valueStringFormat($value);

                if (is_string($newValue)) {
                    
                    if ($this->update($newValue, $key)) {
                        $this->checkEvent($value);
                    }
                }
            }
        }
        
        if (count($this->errors)) {
            return false;
        }
        
        return true;
    }
    
    protected function checkEvent($value) {
        
        $attribute = key($value);
        
        if (isset($this->triggers['attributes'][$attribute])) {
            $model = $this->triggers['model'];
            $func = $this->triggers['attributes'][$attribute];
            $val = $value[$attribute];
            
            call_user_func_array($func, [$val, $model]);
            $model->save();
        }
    }
    
    private function update(string $value, int $id) {
        
        try {
            return Yii::$app->db->createCommand()
                    ->update(EavValue::tableName(), ['value' => $value], ['id' => $id])
                    ->execute();
        } catch(\yii\db\Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
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
                
            } else {

                if (is_array($data)) {
                    
                    $mass = [];

                    foreach ($data as $k=>$old) {
                        
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
                
                $properties = array_keys(get_object_vars($data));
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
                
                if (is_array($data)) {
                    
                    $result = false;
                    
                    foreach ($data as $k=>$old) {
                        
                        $properties = get_object_vars($old);

                        if ($this->compareArrayData($v[$k], $properties)) {
                            $result = true;
                            break;
                        }
                    }
                    

                    return $result;
                }
                
                $properties = get_object_vars($data);

                if ($this->compareArrayData($v, $properties)) {
                    return true;
                }
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

