<?php
namespace common\modules\eav;

use \yii\base\Module as BaseModule;
use Yii;

/*
 * eav init module
 */
class Module extends BaseModule {
    
    public function init() {
        
        parent::init();

        Yii::$container->set('common\modules\eav\contracts\AttributeInterface', $this->components['attribute']);
        Yii::$container->set('common\modules\eav\contracts\AttributeOptionInterface', $this->components['attribute_option']);
        Yii::$container->set('common\modules\eav\contracts\EntityInterface', $this->components['entity']);
        Yii::$container->set('common\modules\eav\contracts\EntityTypeInterface', $this->components['entity_type']);
        Yii::$container->set('common\modules\eav\contracts\ValueInterface', $this->components['value']);
        
    }
}

