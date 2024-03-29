<?php

namespace console\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class CategoryQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}