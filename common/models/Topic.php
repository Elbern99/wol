<?php

namespace common\models;

use Yii;

class Topic extends \yii\db\ActiveRecord
{
    use \common\helpers\FileUploadTrait;
    
    protected $files = [
        'image_link',
    ];
    
    public $article_ids;
    
    protected $sticky_date = null;


    protected $imagePath = '/web/uploads/topics';


    public function getImagePath()
    {
        if ($this->image_link) {
            return Yii::getAlias('@frontend') . $this->imagePath . $this->image_link;
        }
  
        return null;
    }
    
    public function getFrontendPath() {
        return Yii::getAlias('@frontend') . $this->imagePath;
    }
    
    public function getBackendPath() {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title'], 'required'],
            [['description', 'short_description'], 'string'],
            [['created_at', 'is_key_topic', 'sticky_at', 'article_ids'], 'safe'],
            [['url_key'], 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['title'], 'string', 'max' => 255],
            [['url_key'], 'unique'],
            [['image_link'], 'file', 'extensions' => 'jpg, gif, png, bmp, jpeg, jepg', 'skipOnEmpty' => true],
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
            'description' => Yii::t('app', 'Description'),
            'short_decription' => Yii::t('app', 'Short Description'),
            'image_link' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_key_topic' => Yii::t('app', 'Is Key Topic'),
            'sticky_at' => Yii::t('app', 'Is Sticky'),
            'article_ids' => Yii::t('app', 'Articles'),
        ];
    }
    
    public function loadAttributes()
    {
//        $childVideos = RelatedVideo::find()->select('children_id')->where(['=', 'parent_id', $this->id])->all();
//        foreach ($childVideos as $video) {
//            $currentVideo = Video::find()->where(['=', 'id', $video->children_id])->one();
//            $this->video_ids[] = $currentVideo->id; 
//        }
        
        
        $relatedArticles = $this->getTopicArticles()->all();
        
        foreach ($relatedArticles as $article) {
            $currentArticle = $article->article;//Video::find()->where(['=', 'id', $video->children_id])->one();
            $this->article_ids[] = $currentArticle->id; 
        }
        
    }
    
    public function afterFind()
    {
        $this->created_at = new \DateTime($this->created_at);
        
        if ($this->sticky_at) {
            $this->sticky_date = $this->sticky_at;
            $this->sticky_at = true;
        }
        
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->setStickyStatus();
            return true;
        } else {
            return false;
        }
        
    }
    
    public function articlesList()
    {
        $articles = Article::find()->all();
        $articlesList = [];
        foreach ($articles as $article) {
            $articlesList[$article->id] = $article->title; 
        }
        return $articlesList;
    }
    
    public function videosListIds()
    {
        $commentaryVideos = CommentaryVideo::find()->select('video_id')->all();
        $videos = [];
        foreach ($commentaryVideos as $video) {
            $currentVideo = Video::find()->where(['=', 'id', $video->video_id])->one();
            $videos[] = $currentVideo->id; 
        }
        return implode(',', $videos);
    }
    
    public function getTopicArticles()
    {
        return $this->hasMany(TopicArticle::className(), ['topic_id' => 'id']);
    }
    
    public function saveFormatted()
    {
        if (!$this->validate())
            return false;

        $this->setCreatedAtDate();
     
        $this->initUploadProperty();
        $this->setStickyStatus();
        $this->upload();
        
        $this->saveArticlesList();
        
        return $this->save();
    }
    
    protected function setCreatedAtDate()
    {
        $created_at = new \DateTime('now');
        $this->created_at = $created_at->format('Y-m-d');
    }
    
    protected function setStickyStatus()
    {
        if ($this->sticky_date) {
            $this->sticky_at = $this->sticky_date;
        }
        else {
            $sticky_at = new \DateTime('now');
            $this->sticky_at = $sticky_at->format('Y-m-d');
        }
    }
    
    protected function saveArticlesList()
    {
        TopicArticle::deleteAll(['=', 'topic_id', $this->id]);
        
        $bulkInsertArray = [];
        
        if (is_array($this->article_ids)) {
            
            foreach ($this->article_ids as $id) {
                $bulkInsertArray[]=[
                    'topic_id' => $this->id,
                    'article_id' => $id,
                ];
            }

            if (count($bulkInsertArray) > 0){
                $columnNamesArray = ['topic_id', 'article_id'];
                $insertCount = Yii::$app->db->createCommand()
                               ->batchInsert(
                                       TopicArticle::tableName(), $columnNamesArray, $bulkInsertArray
                                 )
                               ->execute();
            }
        }
    }

}
