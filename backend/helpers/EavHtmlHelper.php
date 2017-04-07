<?php
namespace backend\helpers;

use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
use common\modules\eav\StorageEav;
use yii\helpers\ArrayHelper;
use Yii;

class EavHtmlHelper {
    
    private $attributes;
    private $html = '';
    private $langs;
    private $inputModel;
    
    public function __construct($attributes) {
        $this->attributes = $attributes;
        $this->init();
    }
    
    public function render() {
        
        $this->generateAttributeHtml();
        return $this->html;
    }
    
    private function init() {
        
        $langClass = Yii::$app->params['articleModelDetail']['language'];
        $this->langs = ArrayHelper::map($langClass::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
        $eavFactory = new StorageEav();
        $this->inputModel = Html::getInputName($eavFactory->factory('value'), 'value');
    }
    
    protected function generateAttributeHtml() {

        foreach ($this->attributes['attributes'] as $attribute) {
            $this->html .= $this->getAttributeSection($attribute);
        }
    }
    
    protected function getAttributeSection($attribute) {
        
        $section  = Html::beginTag('div', ['class' => 'attribute']);
        $section .= Html::tag('h3', 'Attribute - '.$attribute['label']);
        $section .= $this->getValueInput($attribute);
        $section .= Html::endTag('div');
        
        return $section;
    }
    
    protected function getValueInput($attribute) {
       
        if (!$attribute['value']) {
            return '';
        }

        $values = '';

        if ($attribute['multi_lang_value']) {
            
            $langName = Html::tag('h4', 'English');
            
            foreach ($attribute['value'] as $valId => $value) {
                
                $lang = $value['lang_id'];
                
                if ($lang) {
                    $langName = Html::tag('h3', $this->langs[$lang]);
                }
                
                $values .= $langName;
                
                if (is_array($value['value'])) {
                    foreach ($value['value'] as $k => $column) {
                        $values .= $this->getValue($column, $attribute, $valId, $k);
                    }
                } else {
                    $values .= $this->getValue($value['value'], $attribute, $valId);
                }
                
                $values .= $this->getLangValueInput($attribute, $lang, $valId);
            }

            return $values;
        }
        
        $value = current($attribute['value']);
        $valId = key($attribute['value']);
        
        if (!is_array($value)) {
            return $this->getValue($value, $attribute, $valId);
        }

        foreach ($value as $k=>$column) {
            $values .= $this->getValue($column, $attribute, $valId, $k);
        }

        return $values;
    }
    
    protected function getLangValueInput($attribute, $langId, $valId) {
        
        $name = 'EavValue[value]['.$valId.']['.$attribute['name'].'][lang]';
        return Html::hiddenInput($name, $langId);
    }
    
    protected function getValue($value, $attribute, $valId, $iter = null) {
        
        $group = '';
        
        if (!isset($attribute['options'])) {
            return false;
        }
        
        if (is_null($iter)) {
            $name = $this->inputModel."[".$valId."][".$attribute['name']."]";
        } else {
            $name = $this->inputModel."[".$valId."][".$attribute['name']."][".$iter."]";
        }

        foreach ($attribute['options'] as $option) {
            
            $inputName = 'get'.$option['type'].'Input';
            $property = str_replace(' ', '_', strtolower($option['label']));

            if (!isset($value->$property)) {
                var_dump($property);
                var_dump($value);
                var_dump($attribute);
                exit;
            }
            
            $optionName = $name.'['.$property.']';
            
            $group .= Html::label($option['label']);
            $group .= $this->$inputName($value->$property, $optionName);
        }

        return $group;
    }
    
    
    protected function getStringInput($value, $name) {
        return Html::input('text', $name, $value);
    }
    
    protected function getSmallTextInput($value, $name) {
        return Html::textarea($name, $value, ['rows'=>'5', 'cols'=>'65']);
    }
    
    protected function getTextInput($value, $name) {

        return CKEditor::widget([
                'model' => false,
                'name' => $name,
                'value' => $value,
                'options' => ['rows' => 10],
                'preset' => 'standard',
                'clientOptions' => [
                    'allowedContent' => true,
                    'enterMode' => 2,
                    'forceEnterMode' => false,
                    'shiftEnterMode' => 1
            ]
        ]);
    }
    
    protected function getArrayInput($value, $name) {
        
        $result = [];
        
        foreach ($value as $k=>$item) {
            $newName = $name.'['.$k.']';
            $result[] = $this->getStringInput($item, $newName);
        }
        
        return implode('', $result);
    }

}
