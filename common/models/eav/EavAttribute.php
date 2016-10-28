<?php

namespace common\models\eav;

use Yii;

/**
 * This is the model class for table "eav_attribute".
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property integer $in_search
 * @property integer $required
 * @property integer $enabled
 *
 * @property EavAttributeOption[] $eavAttributeOptions
 * @property EavEntityType[] $eavEntityTypes
 * @property EavValue[] $eavValues
 */
class EavAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eav_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'label'], 'required'],
            [['in_search', 'required', 'enabled'], 'integer'],
            [['name', 'label'], 'string', 'max' => 255],
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
            'label' => Yii::t('app', 'Label'),
            'in_search' => Yii::t('app', 'In Search'),
            'required' => Yii::t('app', 'Required'),
            'enabled' => Yii::t('app', 'Enabled'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttributeOptions()
    {
        return $this->hasMany(EavAttributeOption::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavEntityTypes()
    {
        return $this->hasMany(EavEntityType::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavValues()
    {
        return $this->hasMany(EavValue::className(), ['attribute_id' => 'id']);
    }
}
