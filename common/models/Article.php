<?php

namespace common\models;


use Yii;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityModelInterface;
use common\modules\eav\Collection;
use common\modules\eav\helper\EavAttributeHelper;


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
 * @property int $version 
 * @property boolean $is_current
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

    protected $_collection = null;

    protected $_lang = null;


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
    public function behaviors()
    {
        return [
            [
                'class' => \common\components\TwitterBehavior::className(),
                'twitterCard' => 'summary_large_image',
                'twitterSite' => '@izaworldoflabor',
                'twitterTitle' => 'title',
                'twitterDescription' => 'teaser',
                'twitterImage' => function ($model) {
                    return \yii\helpers\Url::to($model->getElevatorImageUrl(), true);
                },
            ]
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


    public function getCurrentLang()
    {
        return $this->_lang;
    }


    public function setCurrentLang($value)
    {
        $this->_lang = $value;
    }


    public function getElevatorPitch()
    {
        if (!$this->_collection) {
            throw new \yii\base\Exception('Attribute collection not initialized.');
        }

        $collection = $this->getCollection();
        $attributes = $collection->getEntity()->getValues();
        EavAttributeHelper::initEavAttributes($attributes);
        $result = EavAttributeHelper::getAttribute('abstract')->getData('abstract', $this->getCurrentLang());
        return $result;
    }


    public function getTeaser()
    {
        if (!$this->_collection) {
            throw new \yii\base\Exception('Attribute collection not initialized.');
        }

        $collection = $this->getCollection();
        $attributes = $collection->getEntity()->getValues();
        EavAttributeHelper::initEavAttributes($attributes);
        $result = EavAttributeHelper::getAttribute('teaser')->getData('teaser', $this->getCurrentLang());
        return $result;
    }


    public function getElevatorImageUrl()
    {
        if (!$this->_collection) {
            throw new \yii\base\Exception('Attribute collection not initialized.');
        }

        $collection = $this->getCollection();
        $attributes = $collection->getEntity()->getValues();
        EavAttributeHelper::initEavAttributes($attributes);
        $gaImage = EavAttributeHelper::getAttribute('ga_image');
        return $gaImage->getData('path', $this->getCurrentLang());
    }


    /**
     * Incapsulation crutch
     * 
     * @param boolean $multilang
     * @param boolean $forceInit
     * @return Collection
     */
    public function getCollection($multiLang = false, $forceInit = false)
    {
        if (null === $this->_collection || $forceInit) {
            $articleCollection = Yii::createObject(Collection::class);

            $articleCollection->initCollection(self::ENTITY_NAME, $this, $multiLang);
            $this->_collection = $articleCollection;
        }

        return $this->_collection;
    }
}
