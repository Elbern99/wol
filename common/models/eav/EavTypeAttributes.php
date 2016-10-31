<?php

namespace common\models\eav;

use Yii;

/**
 * This is the model class for table "eav_type_attribute".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $attribute_id
 *
 * @property EavAttribute $attribute
 * @property EavType $type
 */
class EavTypeAttributes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eav_type_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'attribute_id'], 'required'],
            [['type_id', 'attribute_id'], 'integer'],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => EavAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EavType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }
    
    public function addAttributeType($typeId, $attributeIds) {

        if (is_array($attributeIds) && count($attributeIds)) {
            
            $options = [];

            foreach ($attributeIds as $attribute) {
                
                $options[] = [
                    'type_id' => $typeId,
                    'attribute_id' => $attribute
                ];
            }

            $insertCount = Yii::$app->db->createCommand()
                        ->batchInsert(
                           self::tableName(), ['type_id', 'attribute_id'], $options
                        )
                        ->execute();
            
            return $this;
        }
        
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavAttribute()
    {
        return $this->hasOne(EavAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(EavType::className(), ['id' => 'type_id']);
    }
}
