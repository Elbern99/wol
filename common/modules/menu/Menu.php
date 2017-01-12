<?php
namespace common\modules\menu;

use common\modules\menu\contracts\MenuManagerInterface;

class Menu {
    
    static $top = null;
    static $main = null;
    static $bottom = null;
    
    public static function instance(MenuManagerInterface $manager) {
        
        self::$top = $manager->getTopMenu();
        self::$bottom = $manager->getBottomMenu();
        self::$main =  $manager->getMainMenu();
    } 
}

