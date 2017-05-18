<?php
namespace console\controllers;

use yii\console\Controller;
use common\models\eav\EavAttribute;
use common\models\eav\EavAttributeOption;
use common\models\eav\EavTypeAttributes;
use common\models\eav\EavType;
use yii\helpers\Console;

class EavController extends Controller {
    
    /* php yii eav/add-attribute param1 param2 ... */
    public function actionAddAttribute($name, $label, $in_search=0, $required=0, $enabled=1) {
        
        $model = new EavAttribute();
        
        $params = [
            'name' => $name,
            'label' => $label,
            'in_search' => $in_search,
            'required' => $required,
            'enabled' => $enabled
        ];
        
        if ($this->modelSave($model, $params, 'Attribute')) {
            return 1;
        }
        
        return 0;
    }
    
    public function actionAddAttributeOption($attrubteName, $label, $type) {
        
        $attribute = EavAttribute::find()->where(['name' => $attrubteName])->one();

        if (is_object($attribute)) {
            
            $model = new EavAttributeOption();

            $params = [
                'attribute_id' => $attribute->id,
                'label' => $label,
                'type' => $type
            ];

            if ($this->modelSave($model, $params, 'Attribute Option')) {
                return 1;
            }
        }
        
        $this->stdout("Attribute Option can not added success", Console::BG_RED);
        echo "\n";

        return 0;
    }
    
    public function actionAddAttributeType($attrubteName, $type) {
        
        $attribute = EavAttribute::find()->where(['name' => $attrubteName])->one();
        $type = EavType::find()->where(['name' => $type])->one();
        
        if (is_object($attribute) && is_object($type)) {
            
            $model = new EavTypeAttributes();

            $params = [
                'attribute_id' => $attribute->id,
                'type_id' => $type->id,
            ];

            if ($this->modelSave($model, $params, 'Attribute Type')) {
                return 1;
            }
        }
        
        $this->stdout("Attribute Option can not added success", Console::BG_RED);
        echo "\n";

        return 0;
    }
    
    protected function modelSave($model, $params, $actopn) {

        $model->load($params, '');

        if ($model->validate()) {
            $model->save();

            $this->stdout($actopn." added success", Console::BG_GREEN);
            echo "\n";
            return 1;
        }


        $this->stdout($actopn." can not added success", Console::BG_RED);
        echo "\n";

        foreach ($model->getErrors() as $error) {
            $this->stdout($error[0], Console::BG_RED);
            echo "\n";
        }

        return 0;
    }
}