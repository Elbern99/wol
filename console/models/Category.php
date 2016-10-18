<?php

namespace console\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use console\models\CategoryQuery;

class Category extends ActiveRecord {

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'root',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'lvl',
            ],
        ];
    }

    public function transactions() {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find() {
        return new CategoryQuery(get_called_class());
    }

    public static function tableName() {
        return 'category';
    }

}
