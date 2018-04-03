<?php

namespace common\components;


trait ModelFirstErrorTrait
{


    /**
     * @return string
     */
    protected function getFirstErrorMessage()
    {
        $errors = $this->errors;
        $errors = reset($errors);
        return reset($errors);
    }
}
