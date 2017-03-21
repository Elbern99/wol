<?php
namespace common\modules\blocks\contracts;

interface BlockRepositoryInterface {
    
    public function getWidget(string $name);
}

