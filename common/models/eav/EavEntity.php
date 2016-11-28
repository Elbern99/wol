<?php

namespace common\models\eav;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eav_entity".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $type_id
 * @property string $name
 *
 * @property EavEntityType $type
 * @property EavValue[] $eavValues
 */
class EavEntity extends \yii\db\ActiveRecord implements \common\modules\eav\contracts\EntityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eav_entity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'type_id', 'name'], 'required'],
            [['model_id', 'type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['model_id', 'type_id'], 'unique', 'targetAttribute' => ['model_id', 'type_id'], 'message' => 'The combination of Model ID and Type ID has already been taken.'],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EavType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_id' => Yii::t('app', 'Model ID'),
            'type_id' => Yii::t('app', 'Type ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(EavEntityType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEavValues()
    {
        return $this->hasMany(EavValue::className(), ['entity_id' => 'id']);
    }
    
    public function getEavValueLanguage() {

        $query = EavValue::find()->select(['lang_id'])
                        ->where(['entity_id' => $this->id])
                        ->groupBy('lang_id')
                        ->asArray()
                        ->all();
        
        return ArrayHelper::getColumn($query, 'lang_id');
    }
    
    public function addEntity(array $args) {
        
        $obj = Yii::createObject(self::class);
        $obj->load($args, '');

        if ($obj->save()) {
            return $obj;
        }

        return null;
    }
}
