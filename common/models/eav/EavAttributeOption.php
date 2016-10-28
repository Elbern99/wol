<?php

namespace common\models\eav;

use Yii;

/**
 * This is the model class for table "eav_attribute_option".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property string $label
 * @property integer $type
 *
 * @property EavAttribute $attribute
 */
class EavAttributeOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eav_attribute_option';
    }
    
    public function getTypeByName($type) {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'label', 'type'], 'required'],
            [['attribute_id', 'type'], 'integer'],
            [['label'], 'string', 'max' => 255],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => EavAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'label' => Yii::t('app', 'Label'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttribute()
    {
        return $this->hasOne(EavAttribute::className(), ['id' => 'attribute_id']);
    }
}
