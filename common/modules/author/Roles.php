<?php
namespace common\modules\author;

use common\components\Type;

class Roles extends Type {
    
    public function __construct() {
        
        $types = [
            1 => 'author',
            2 => 'expert',
            3 => 'chiefEditor',
            4 => 'managingEditor',
            5 => 'subjectEditor',
            6 => 'associateEditor',
            7 => 'formerEditor'
        ];
        
        $this->addTypes($types);
    }
}

