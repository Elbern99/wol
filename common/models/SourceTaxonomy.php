<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "source_taxonomy".
 *
 * @property integer $id
 * @property integer $source_id
 * @property integer $taxonomy_id
 *
 * @property DataSource $source
 * @property Taxonomy $taxonomy
 */
class SourceTaxonomy extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_taxonomy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_id', 'taxonomy_id'], 'integer'],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataSource::className(), 'targetAttribute' => ['source_id' => 'id']],
            [['taxonomy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Taxonomy::className(), 'targetAttribute' => ['taxonomy_id' => 'id']],
            [['additional_taxonomy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Taxonomy::className(), 'targetAttribute' => ['additional_taxonomy_id' => 'id']],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'source_id' => Yii::t('app', 'Source ID'),
            'taxonomy_id' => Yii::t('app', 'Taxonomy ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(DataSource::className(), ['id' => 'source_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxonomy()
    {
        return $this->hasOne(Taxonomy::className(), ['id' => 'taxonomy_id']);
    }
    
    public function getAdditionalTaxonomy()
    {
        return $this->hasOne(Taxonomy::className(), ['id' => 'additional_taxonomy_id']);
    }
}
