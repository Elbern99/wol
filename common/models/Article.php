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
 * @property string $keywords
 * @property integer $enabled
 * @property integer $created_at
 * @property integer $updated_at
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'enabled', 'created_at', 'updated_at'], 'integer'],
            [['sort_key', 'seo', 'doi', 'created_at', 'updated_at'], 'required'],
            [['sort_key', 'seo'], 'string', 'max' => 255],
            [['doi', 'availability'], 'string', 'max' => 50],
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
