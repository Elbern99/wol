<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
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
    
    public static function addSources(array $sources) {
        
        $bulkInsertSourceTaxonomyArray = [];
        
        foreach ($sources as $source) {

            $model = self::find()->andFilterWhere(['source' => $source->source])->one();

            if (!$model) {
                
                $model = new DataSource();
                
                $model->website = $source->website;
                $model->source = $source->source;
                $model->types = $source->types;
                
                if (!$model->save()) {
                    continue;
                }
            }
            
            $currentTypes = SourceTaxonomy::find()
                                            ->select('taxonomy_id')
                                            ->where(['source_id' => $model->id])
                                            ->asArray()
                                            ->all();
            if (isset($source->types['col'])) {
                $col = $source->types['col'];
            } else {
                $col = null;
            }

            if (array_search($col, ArrayHelper::getColumn($currentTypes, 'taxonomy_id')) === false) {
                    
                $dim = $source->types['dim'] ?? null;

                $bulkInsertSourceTaxonomyArray[] = [
                    'source_id' => $model->id, 
                    'taxonomy_id' => $col,
                    'additional_taxonomy_id' => $dim
                ];
            }
        }
       
        if (count($bulkInsertSourceTaxonomyArray)) {
            Yii::$app->db->createCommand()->batchInsert(
                SourceTaxonomy::tableName(), [
                    'source_id', 'taxonomy_id', 'additional_taxonomy_id'
                ], 
                $bulkInsertSourceTaxonomyArray
            )->execute();
        }
    }
}
