<?php
namespace common\modules\author;

use common\components\Type;

class Roles extends Type {
    
    protected $groups = [];
    
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
        
        $this->groups['editor'] = [3,4,5,6,7];
        
        $this->addTypes($types);
    }
    
    public function getEditorGroup() {
        
        return $this->groups['editor'];
    }
}

