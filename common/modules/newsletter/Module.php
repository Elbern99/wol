<?php
namespace common\modules\newsletter;

use Yii;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        
        Yii::$container->set('newsletter', function () {
            $newslatter = Yii::createObject($this->components['newsletter_facade'],[Yii::createObject($this->components['newsletter_model'])]);
            return $newslatter;
        });

    }
}

