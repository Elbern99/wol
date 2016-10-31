<?php

namespace common\modules\eav\contracts;

interface AttributeInterface {
    public function getAttributeSchema();
    public function addAttributeWithOptions($attribute);
}

