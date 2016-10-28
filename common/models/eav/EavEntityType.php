<?php

namespace common\models\eav;

use Yii;

/**
 * This is the model class for table "eav_entity_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $attribute_id
 *
 * @property EavEntity[] $eavEntities
 * @property EavAttribute $attribute
 */
class EavEntityType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eav_entity_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id'], 'required'],
            [['attribute_id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique'],
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
            'name' => Yii::t('app', 'Name'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavEntities()
    {
        return $this->hasMany(EavEntity::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute()
    {
        return $this->hasOne(EavAttribute::className(), ['id' => 'attribute_id']);
    }
}
