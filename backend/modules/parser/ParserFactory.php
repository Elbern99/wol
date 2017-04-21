<?php

namespace backend\modules\parser;

use common\contracts\ReaderInterface;
use common\contracts\ParserInterface;
use Yii;

/*
 * class create parser object by type
 */
class ParserFactory {
    
    public function createReader(): ReaderInterface {
        return Yii::createObject(Reader::class);
    }
    
    public function createParser($class): ParserInterface {
       
        if (class_exists($class)) {
            return Yii::createObject($class);
        }
        
        throw new \Exception('Class did not found');
    }
}

