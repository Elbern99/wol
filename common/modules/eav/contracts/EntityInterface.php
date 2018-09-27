<?php

namespace common\modules\eav\contracts;

interface EntityInterface {
    public function addEntity(array $args, $allowOverride = false);
}

