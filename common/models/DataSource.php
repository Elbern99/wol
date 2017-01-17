<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data_source".
 *
 * @property integer $id
 * @property string $source
 * @property string $website
 *
 * @property SourceTaxonomy[] $sourceTaxonomies
 */
class DataSource extends \yii\db\ActiveRecord
{
    public $types = [];
    protected $sourceCode = 'IWOL_COL_40';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source', 'website', 'types'], 'required'],
            [['source', 'website'], 'string', 'max' => 255],
            ['types', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'source' => Yii::t('app', 'Source'),
            'website' => Yii::t('app', 'Website'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceTaxonomies()
    {
        return $this->hasMany(SourceTaxonomy::className(), ['source_id' => 'id']);
    }
    
    public function getItems() {
        
        $data = Taxonomy::find()
                          ->select(['value', 'id'])
                          ->andFilterWhere(['like', 'code', $this->sourceCode])
                          ->asArray()
                          ->all();
        
        return \yii\helpers\ArrayHelper::map($data, 'id', 'value');
    }
}
