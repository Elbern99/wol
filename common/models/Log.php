<?php

namespace common\models;

use common\contracts\LogInterface;

/**
 * Description of Log
 *
 * @author user
 */
class Log implements LogInterface {
    
    private $log = [];
    
    public function addLine(string $line){
        $this->log[] = $line;
    }
    
    public function getLog():array {
        return $this->log;
    }
    
    public function getCount():int {
        return count($this->log);
    }
}
