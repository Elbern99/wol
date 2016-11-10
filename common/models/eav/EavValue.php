<?php

namespace common\models\eav;

use Yii;

/**
 * This is the model class for table "eav_value".
 *
 * @property integer $id
 * @property integer $entity_id
 * @property integer $attribute_id
 * @property integer $lang_id
 * @property string $value
 *
 * @property EavAttribute $attribute
 * @property EavEntity $entity
 */
class EavValue extends \yii\db\ActiveRecord implements \common\modules\eav\contracts\ValueInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eav_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'attribute_id'], 'required'],
            [['entity_id', 'attribute_id'], 'integer'],
            [['lang_id'], 'safe'],
            [['value'], 'string'],
            [['attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => EavAttribute::className(), 'targetAttribute' => ['attribute_id' => 'id']],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => EavEntity::className(), 'targetAttribute' => ['entity_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'value' => Yii::t('app', 'Value'),
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
    public function getEntity()
    {
        return $this->hasOne(EavEntity::className(), ['id' => 'entity_id']);
    }
    
    public function addEavAttribute(array $args) {
        
        $obj = Yii::createObject(self::class);
        $obj->load($args, '');

        if ($obj->save()) {
            return true;
        }

        return false;
    }
}
