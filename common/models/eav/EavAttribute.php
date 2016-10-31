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
class EavAttribute extends \yii\db\ActiveRecord implements \common\modules\eav\contracts\AttributeInterface
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
    
    public function getAttributeSchema() {
        
        return [
            'name',
            'label',
            'in_search',
            'required',
            'enabled'
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
    
    public function addAttributeWithOptions($attribute) {
        
        $this->load($attribute->getParams(), '');

        if ($this->validate()) {
            
            $this->save();
            $options = [];

            foreach ($attribute->getOptions() as $option) {
                $option['attribute_id'] = $this->id;
                $options[] = $option;
            }
           
            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                       EavAttributeOption::tableName(), ['label', 'type', 'attribute_id'], $options
                    )
                    ->execute();
            
            return $this;
        }
        
        return false;
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
    public function getEavAttributeTypes()
    {
        return $this->hasMany(EavTypeAttributes::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavValues()
    {
        return $this->hasMany(EavValue::className(), ['attribute_id' => 'id']);
    }
}
