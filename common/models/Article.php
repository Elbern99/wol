<?php

namespace common\models;


use Yii;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityModelInterface;
use common\modules\eav\Collection;
use common\modules\eav\helper\EavAttributeHelper;
use yii\helpers\Url;
use common\modules\eav\StorageEav;


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
 * @property boolean $version_updated_label
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

    const VERSION_PARAM = 'v';

    public $simpleDelete = false;

    protected $_collection = null;

    protected $_lang = null;

    protected $_maxVersion = null;

    protected $_anotherVersions = null;


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
            [['enabled', 'version', 'article_number'], 'integer'],
            [['sort_key', 'seo', 'doi', 'article_number', 'version'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['sort_key', 'seo', 'title', 'revision_description'], 'string', 'max' => 255],
            ['notices', 'string'],
            [['doi', 'availability', 'publisher'], 'string', 'max' => 50],
            [['id'], 'unique'],
            ['version_updated_label', 'boolean']
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
            'version_updated_label' => Yii::t('app', 'Add Updated Indicator')
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
        return $this->hasMany(ArticleAuthor::className(), ['article_id' => 'id']);
    }


    public function getAuthors()
    {
        return $this->hasMany(Author::className(), ['id' => 'author_id'])->viaTable(ArticleAuthor::tableName(), ['article_id' => 'id']);
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
                ->where(['article_number' => $ids, 'enabled' => 1, 'is_current' => 1])
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
        // DO NOT USE, not implemented, deprecated
        return VersionsArticle::find()
                ->select(['version_number', 'seo', 'notices'])
                ->where(['article_id' => $this->id])
                ->asArray()
                ->all();
    }


    public function getVersions()
    {
        return $this
                ->hasMany(Article::className(), ['article_number' => 'article_number'])
                ->andOnCondition('version.id <> :self', [':self' => $this->id])
                ->andOnCondition('version.enabled=1')
                ->from(['version' => Article::tableName()])
                ->orderBy('version DESC');
    }

    public function getAllVersions()
    {
        return $this
                ->hasMany(Article::className(), ['article_number' => 'article_number'])
                ->andOnCondition('version.enabled=1')
                ->from(['version' => Article::tableName()])
                ->orderBy('version DESC');
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


    /**
     * @return string
     */
    public function getUrlOnePager()
    {
        return Url::to($this->prepareRoute(['article/one-pager', 'slug' => $this->seo]));
    }


    /**
     * @return string
     */
    public function getUrlFull()
    {
        return Url::to($this->prepareRoute(['article/full', 'slug' => $this->seo]));
    }


    /**
     * @return string
     */
    public function getUrlMap()
    {
        return Url::to($this->prepareRoute(['article/map', 'slug' => $this->seo]));
    }


    /**
     * @return string
     */
    public function getUrlLang($lang)
    {
        return Url::to($this->prepareRoute(['article/lang', 'slug' => $this->seo, 'code' => $lang]));
    }


    public function getFullDoi()
    {
        return $this->version > 1 ? $this->doi . '.v' . $this->version : $this->doi;
    }


    public function getMaxVersion()
    {
        if ($this->isNewRecord || !$this->id || !$this->article_number) {
            throw new \yii\base\Exception('The article is not saved yet.');
        }

        if (null === $this->_maxVersion) {
            $this->_maxVersion = Article::find()
                ->where(['article_number' => $this->article_number])
                ->andWhere(['enabled' => 1])
                ->max('version');
        }

        return $this->_maxVersion;
    }


    public function getAnotherVersions($enabled = false)
    {
        if ($this->isNewRecord || !$this->id || !$this->article_number) {
            throw new \yii\base\Exception('The article is not saved yet.');
        }

        if (null == $this->_anotherVersions) {
            $query = Article::find()->where(['article_number' => $this->article_number])
                ->andWhere(['NOT', ['id' => $this->id]]);

            if ($enabled) {
                $query->andWhere(['enabled' => 1]);
            }

            $this->_anotherVersions = $query->all();
        }

        return $this->_anotherVersions;
    }

    /**
     * Check if version update label should be display.
     *
     * @return bool return `true` if it needs to display.
     * @throws \yii\base\Exception
     */
    public function isShowVersionLabel()
    {
        if (!$this->version_updated_label ||
            $this->version == 1 ||
            $this->version != $this->getMaxVersion()) {
            return false;
        }

        return true;
    }

    public function getAuthorList()
    {
        $authors = [];

        if (count($this->authors)) {
            foreach ($this->authors as $author) {
                $authors[] = \yii\helpers\Html::a($author->name, $author->url);
            }
        } else {
            $authors[] = $this->availability;
        }

        return $authors;
    }


    public function delete()
    {
        $version = $this->version;
        $articleNulber = $this->article_number;
        $anotherVersions = $this->getAnotherVersions();

        parent::delete();

        if ($version == 1) {
            foreach ($anotherVersions as $av) {
                $av->simpleDelete = true;

                $eavFactory = new StorageEav();
                $eavEntityModel = $eavFactory->factory('entity');
                $eavTypeModel = $eavFactory->factory('type');

                $entity = $eavEntityModel->find()
                    ->alias('e')
                    ->innerJoin(['t' => $eavTypeModel::tableName()], 'e.type_id = t.id')
                    ->where(['e.model_id' => $av->id, 't.name' => 'article'])
                    ->one();

                if (!empty($entity->id)) {
                    $entity->delete();
                }

                $av->delete();
            }
        } elseif (!$this->simpleDelete) {
            \common\helpers\ArticleHelper::setupCurrent($articleNulber);
            \yii\base\Event::trigger(\common\modules\article\ArticleParser::class, \common\modules\article\ArticleParser::EVENT_SPHINX_REINDEX);
        }
    }


    /**
     * Add version to route if article is not current
     * 
     * @param array $route
     * @return array
     */
    protected function prepareRoute(array $route)
    {
        if (!$this->is_current) {
            $route[self::VERSION_PARAM] = $this->version;
        }

        return $route;
    }
}
