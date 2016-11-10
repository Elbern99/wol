<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $image
 */
class Lang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'image'], 'required'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 15],
            [['image'], 'string', 'max' => 50],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'image' => Yii::t('app', 'Image'),
        ];
    }
}
