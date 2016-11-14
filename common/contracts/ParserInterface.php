<?php
namespace common\contracts;

use common\contracts\ReaderInterface;

/*
 * interface for parser class
 */
interface ParserInterface {
    public function parse(ReaderInterface $reader) ;
}

