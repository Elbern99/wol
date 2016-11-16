<?php

namespace common\models;

use common\contracts\TaxonomyInterface;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "taxonomy".
 *
 * @property integer $id
 * @property string $code
 * @property integer $parent_id
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class Taxonomy extends \yii\db\ActiveRecord implements TaxonomyInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'taxonomy';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'value', 'created_at'], 'required'],
            [['parent_id', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'string'],
            [['code'], 'string', 'max' => 255],
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'value' => Yii::t('app', 'Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
