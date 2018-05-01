<?php

namespace common\models;


use Yii;
use yii\web\UploadedFile;
use yii\helpers\Html;


class NewsItem extends \yii\db\ActiveRecord
{

    use \common\helpers\FileUploadTrait;

    protected $files = [
        'image_link',
    ];

    protected $imagePath = '/web/uploads/news';

    public $article_ids;

    public $editor;


    public function getImagePath()
    {
        if ($this->image_link) {
            return Yii::getAlias('@frontend') . $this->imagePath . '/' . $this->image_link;
        }

        return null;
    }


    public function getImageUrl($absolute = false)
    {
        $url = $this->image_link ? '/uploads/news/' . $this->image_link : false;

        if ($absolute && $url) {
            $url = Yii::$app->urlManager->createAbsoluteUrl($url);
        }

        return $url;
    }


    public function getFrontendPath()
    {
        // return Yii::getAlias('@frontend').'/web/uploads/news';
        return Yii::getAlias('@frontend') . $this->imagePath;
    }


    public function getBackendPath()
    {
        return null;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
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
                'twitterDescription' => 'short_description',
                'twitterImage' => function ($model) {
                    return $model->getImageUrl(true);
                },
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title'], 'required'],
            [['description', 'short_description'], 'string'],
            [['created_at', 'article_ids', 'sources'], 'safe'],
            [['url_key'], 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['title'], 'string', 'max' => 255],
            [['url_key'], 'unique'],
            [['image_link'], 'file', 'extensions' => 'jpg, gif, png, bmp, jpeg, jepg', 'skipOnEmpty' => true],
            [['enabled'], 'integer']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url_key' => Yii::t('app', 'Url Key'),
            'title' => Yii::t('app', 'Title'),
            'sources' => Yii::t('app', 'Sources'),
            'description' => Yii::t('app', 'Description'),
            'short_decription' => Yii::t('app', 'Short Description'),
            'image_link' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'article_ids' => Yii::t('app', 'Related Articles'),
        ];
    }


    public function loadAttributes()
    {
        $relatedArticles = $this->getNewsArticles()->all();

        foreach ($relatedArticles as $article) {
            $currentArticle = $article->article;
            $this->article_ids[] = $currentArticle->id;
        }
    }


    public function afterFind()
    {
        $this->created_at = new \DateTime($this->created_at);
        $this->editor = self::getSourcesLink($this->sources);
    }


    public static function getSourcesLink($sources)
    {

        $str = [];

        if ($sources) {

            $sources = unserialize($sources);

            foreach ($sources as $source) {

                if ($source['link']) {
                    $str[] = Html::a($source['source'], urldecode($source['link']), ['target' => 'blank']);
                } else {
                    $str[] = $source['source'];
                }
            }
        }

        return implode(', ', $str);
    }


    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            if (is_array($this->sources) && count($this->sources)) {
                $sources = [];

                foreach ($this->sources as $source) {
                    if (!$source['source']) {
                        continue;
                    }

                    $sources[] = [
                        'source' => $source['source'],
                        'link' => ($source['link']) ? urlencode(urldecode($source['link'])) : null
                    ];
                }

                $this->sources = serialize($sources);
            }

            $this->checkImageLink();
            return true;
        }

        return false;
    }


    public function deleteImage()
    {
        if ($this->image_link) {
            if (file_exists($this->getImagePath())) {
                unlink($this->getImagePath());
            }

            Yii::$app->db->createCommand()
                ->update(self::tableName(), ['image_link' => ''], 'id = ' . $this->id)
                ->execute();
        }
    }


    public function checkImageLink()
    {
        $image = UploadedFile::getInstance($this, 'image_link');

        if (!$image) {
            $currentItem = self::find()->where(['id' => $this->id])->one();
            if ($currentItem && $currentItem->image_link) {
                $this->image_link = $currentItem->image_link;
            }
        }
    }


    public function getNewsArticles()
    {
        return $this->hasMany(NewsArticle::className(), ['news_id' => 'id']);
    }


    public function articlesList()
    {
        $articles = Article::find()->where(['enabled' => 1])->orderBy('id desc')->all();
        $articlesList = [];
        foreach ($articles as $article) {
            $articlesList[$article->id] = $article->title;
        }
        return $articlesList;
    }


    public function saveFormatted()
    {
        if (!$this->validate())
            return false;

        $this->setCreatedAtDate();
        $this->initUploadProperty();
        $this->upload();

        if ($this->save()) {
            $this->saveArticlesList();
        }

        return true;
    }


    protected function setCreatedAtDate()
    {
        $date = $this->created_at ? $this->created_at : 'now';

        try {
            $created_at = new \DateTime($date);
        } catch (\Exception $e) {
            $created_at = new \DateTime('now');
        }
        $this->created_at = $created_at->format('Y-m-d');
    }


    protected function saveArticlesList()
    {
        NewsArticle::deleteAll(['=', 'news_id', $this->id]);

        $bulkInsertArray = [];

        if (is_array($this->article_ids)) {

            foreach ($this->article_ids as $id) {
                $bulkInsertArray[] = [
                    'news_id' => $this->id,
                    'article_id' => $id,
                ];
            }

            if (count($bulkInsertArray) > 0) {
                $columnNamesArray = ['news_id', 'article_id'];
                $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        NewsArticle::tableName(), $columnNamesArray, $bulkInsertArray
                    )
                    ->execute();
            }
        }
    }
}
