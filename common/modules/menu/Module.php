<?php
namespace common\modules\menu;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        
        $container = new \yii\di\Container();
        $container->setSingleton('\common\modules\menu\contracts\MenuManagerInterface', $this->components['menu_manager']);
        Menu::instance($container->get('\common\modules\menu\contracts\MenuManagerInterface'));
    }
}

