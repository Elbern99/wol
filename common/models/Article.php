<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $sort_key
 * @property string $seo
 * @property string $doi
 * @property string $availability
 * @property integer $enabled
 * @property string $created_at
 * @property string $updated_at
 * @property string $publisher
 *
 * @property ArticleAuthor[] $articleAuthors
 * @property ArticleCategory[] $articleCategories
 * @property ArticleRelation[] $articleRelations
 * @property ArticleRelation[] $articleRelations0
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }
    
    public static function primaryKey()
    {
        return [0=>'id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','enabled'], 'integer'],
            [['sort_key', 'seo', 'doi'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['sort_key', 'seo'], 'string', 'max' => 255],
            [['doi', 'availability', 'publisher'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sort_key' => Yii::t('app', 'Sort Key'),
            'seo' => Yii::t('app', 'Seo'),
            'doi' => Yii::t('app', 'Doi'),
            'availability' => Yii::t('app', 'Availability'),
            'enabled' => Yii::t('app', 'Enabled'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'publisher' => Yii::t('app', 'Publisher'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
        return $this->hasMany(ArticleAuthor::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategories()
    {
        return $this->hasMany(ArticleCategory::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleRelations()
    {
        return $this->hasMany(ArticleRelation::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleRelations0()
    {
        return $this->hasMany(ArticleRelation::className(), ['article_relation_id' => 'id']);
    }
}
