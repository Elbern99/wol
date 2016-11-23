<?php
namespace common\modules\settings;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        
       SettingsRepository::initSettings();
    }
}

