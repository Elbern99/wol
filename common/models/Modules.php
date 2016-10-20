<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "modules".
 *
 * @property integer $id
 * @property string $key
 * @property string $title
 * @property integer $system
 *
 * @property CmsPages[] $cmsPages
 */
class Modules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'title'], 'required'],
            [['system'], 'integer'],
            [['key'], 'string', 'max' => 40],
            [['title'], 'string', 'max' => 50],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'title' => Yii::t('app', 'Title'),
            'system' => Yii::t('app', 'System'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsPages()
    {
        return $this->hasMany(CmsPages::className(), ['modules_id' => 'id']);
    }
}
