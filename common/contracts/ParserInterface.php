<?php
namespace common\contracts;

use common\contracts\ReaderInterface;

interface ParserInterface {
    public function parse(ReaderInterface $reader) ;
}

