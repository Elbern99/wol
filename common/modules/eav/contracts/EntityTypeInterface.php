<?php

namespace common\modules\eav\contracts;

interface EntityTypeInterface {
    public function addType($name);
    public function getEavTypeAttributes();
}

