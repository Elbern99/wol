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
        $this->groups['author'] = [1];
        $this->groups['expert'] = [2];
        $this->addTypes($types);
    }
    
    public function getEditorGroup() {
        return $this->groups['editor'];
    }
    
    public function getAuthorGroup() {
        return $this->groups['author'];
    }
    
    public function getExpertGroup() {
        return $this->groups['expert'];
    }
}

