<?php

namespace common\models\eav;

use Yii;

/**
 * This is the model class for table "eav_type".
 *
 * @property integer $id
 * @property string $name
 *
 * @property EavEntity[] $eavEntities
 * @property EavTypeAttribute[] $eavTypeAttributes
 */
class EavType extends \yii\db\ActiveRecord implements \common\modules\eav\contracts\EntityTypeInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eav_type';
    }
    
    public function addType($name) {
        
        $this->load(['name'=>$name], '');
        if ($this->validate()) {
            $this->save();
        }
        
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 30],
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
    public function getEavTypeAttributes()
    {
        return $this->hasMany(EavTypeAttribute::className(), ['type_id' => 'id']);
    }
}
