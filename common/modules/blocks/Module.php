<?php
namespace common\modules\blocks;

use Yii;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        
        $model = $this->components['repository_model'];
        
        Yii::$container->setSingleton('blocks', function () use ($model) {
            return Yii::createObject($model);
        });
    }
}

