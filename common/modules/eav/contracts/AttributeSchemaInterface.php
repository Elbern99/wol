<?php

namespace common\modules\eav\contracts;

interface AttributeSchemaInterface {
    
    public function __construct($name, $params, $options);
    public function getOptions();
    public function getName();
    public function getparams();
    
}


