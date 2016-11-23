<?php
namespace common\modules\menu\contracts;

interface MenuManagerInterface {
    
    public function getTopMenu();
    public function getMainMenu();
    public function getBottomMenu();
}

