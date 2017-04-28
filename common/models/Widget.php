<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "widget".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 */
class Widget extends \yii\db\ActiveRecord
{
    public $order;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'widget';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['text'], 'string'],
            [['name'], 'string', 'max' => 200],
            ['name', 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'text' => Yii::t('app', 'Text'),
        ];
    }
}
