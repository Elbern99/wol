<?php

namespace common\models;

use Yii;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityModelInterface;

/**
 * This is the model class for table "versions_article".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $version_number
 * @property string $sort_key
 * @property string $seo
 * @property string $doi
 * @property string $availability
 * @property integer $enabled
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $publisher
 * @property string $title
 * @property resource $notices
 *
 * @property Article $article
 */
class VersionsArticle extends \yii\db\ActiveRecord implements ArticleInterface, EntityModelInterface
{
    const ENTITY_NAME = 'versions';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'versions_article';
    }
    
    public function getId() {
        return $this->id;
    }
    
    public static function getBaseFolder() {
        return 'articles';
    }
    
    public function getSavePath() {
        return '/uploads/'. self::getBaseFolder() .'/'.$this->id;
    }
    
    public function getFrontendImagesBasePath() {
        return Yii::getAlias('@frontend').'/web/uploads/'. self::getBaseFolder() .'/'.$this->id.'/images/';
    }
    
    public function getBackendImagesBasePath() {
        return Yii::getAlias('@backend').'/web/uploads/'. self::getBaseFolder() .'/'.$this->id.'/images/';
    }
    
    public function getFrontendPdfsBasePath() {
        return Yii::getAlias('@frontend') . '/web/uploads/' . self::getBaseFolder() . '/' . $this->id . '/pdfs/';
    }
    
    public function getBackendPdfsBasePath() {
        return Yii::getAlias('@backend') . '/web/uploads/' . self::getBaseFolder() . '/' . $this->id . '/pdfs/';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'version_number', 'sort_key', 'seo', 'doi', 'title'], 'required'],
            [['article_id', 'version_number', 'enabled', 'created_at', 'updated_at'], 'integer'],
            [['notices'], 'string'],
            [['sort_key', 'seo', 'title'], 'string', 'max' => 255],
            [['doi', 'availability', 'publisher'], 'string', 'max' => 50],
            [['seo'], 'unique'],
            //[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'article_id' => Yii::t('app', 'Article ID'),
            'version_number' => Yii::t('app', 'Version Number'),
            'sort_key' => Yii::t('app', 'Sort Key'),
            'seo' => Yii::t('app', 'Seo'),
            'doi' => Yii::t('app', 'Doi'),
            'availability' => Yii::t('app', 'Availability'),
            'enabled' => Yii::t('app', 'Enabled'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'publisher' => Yii::t('app', 'Publisher'),
            'title' => Yii::t('app', 'Title'),
            'notices' => Yii::t('app', 'Notices'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
        return $this->hasMany(ArticleAuthor::className(), ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategories()
    {
        return $this->hasMany(ArticleCategory::className(), ['article_id' => 'article_id']);
    }
    
    public function getRelatedVersions() {
        
        return VersionsArticle::find()
                    ->select(['version_number','seo','notices'])
                    ->where(['<>', 'id', $this->getId()])
                    ->andWhere(['article_id' => $this->article_id])
                    ->asArray()
                    ->all();
    }
}
