<?php

namespace common\models;


use Yii;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityModelInterface;


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
 * @property ArticleCreated $created
 */
class Article extends \yii\db\ActiveRecord implements ArticleInterface, EntityModelInterface
{


    const ENTITY_NAME = 'article';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }


    public static function primaryKey()
    {
        return [0 => 'id'];
    }


    public function getId()
    {
        return $this->id;
    }


    public static function getBaseFolder()
    {
        return 'articles';
    }


    public function getSavePath()
    {
        return '/uploads/' . self::getBaseFolder() . '/' . $this->id;
    }


    public function getFrontendImagesBasePath()
    {
        return Yii::getAlias('@frontend') . '/web/uploads/' . self::getBaseFolder() . '/' . $this->id . '/images/';
    }


    public function getBackendImagesBasePath()
    {
        return Yii::getAlias('@backend') . '/web/uploads/' . self::getBaseFolder() . '/' . $this->id . '/images/';
    }


    public function getFrontendPdfsBasePath()
    {
        return Yii::getAlias('@frontend') . '/web/uploads/' . self::getBaseFolder() . '/' . $this->id . '/pdfs/';
    }


    public function getBackendPdfsBasePath()
    {
        return Yii::getAlias('@backend') . '/web/uploads/' . self::getBaseFolder() . '/' . $this->id . '/pdfs/';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'enabled'], 'integer'],
            [['id', 'sort_key', 'seo', 'doi'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['sort_key', 'seo', 'title'], 'string', 'max' => 255],
            ['notices', 'string'],
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
            'title' => Yii::t('app', 'Title'),
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
    public function getCreated()
    {
        return $this->hasOne(ArticleCreated::className(), ['article_id' => 'id']);
    }


    public function getRelatedArticles(array $articles)
    {
        $ids = [];
        $related = [];

        foreach ($articles as $article) {
            $ids[] = $article->id;
        }

        if (count($ids)) {
            $related = $this
                ->find()
                ->where(['id' => $ids, 'enabled' => 1])
                ->with(['articleAuthors.author' => function($query) {
                        return $query->select(['id', 'name', 'url_key']);
                    }])
                ->select(['id', 'seo', 'title'])
                ->all();

            unset($ids);
        }

        if (count($related)) {

            $formatRelated = [];

            foreach ($related as $article) {

                $authors = [];

                foreach ($article->articleAuthors as $author) {

                    if (isset($author->author)) {
                        $authors[] = [
                            'name' => $author->author->name,
                            'url' => Author::getAuthorUrl($author->author->url_key)
                        ];
                    }
                }

                $formatRelated[] = [
                    'seo' => $article->seo,
                    'title' => $article->title,
                    'authors' => $authors
                ];
            }

            return $formatRelated;
        }

        return $related;
    }


    public function getArticleVersions()
    {

        return VersionsArticle::find()
                ->select(['version_number', 'seo', 'notices'])
                ->where(['article_id' => $this->id])
                ->asArray()
                ->all();
    }


    public function getRelatedCategories()
    {
        
    }


    /**
     * @return \common\models\ArticleCreated
     */
    public function insertOrUpdateCreateRecord()
    {
        $model = null;

        if ($this->id) {
            $model = ArticleCreated::findOne(['article_id' => $this->id]);

            if (!$model) {
                $model = new ArticleCreated();
                $model->article_id = $this->id;
                $model->doi_control = $this->doi;
                $model->save();
            }
        }

        return $model;
    }
}
