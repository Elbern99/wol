<?php
namespace common\modules\menu;

use common\modules\menu\contracts\MenuManagerInterface;

class Menu {
    
    static $top = null;
    static $main = null;
    static $bottom = null;
    
    public static function instance(MenuManagerInterface $manager) {

        $data = $manager->getData();
        
        self::$top = $data['top'];
        self::$bottom = $data['bottom'];
        self::$main =  $data['main'];
    } 
}

