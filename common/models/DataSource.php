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
            [['source', 'website'], 'required'],
            [['source', 'website'], 'string', 'max' => 255],
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
}
