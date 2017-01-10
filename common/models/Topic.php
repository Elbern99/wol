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
    public $video_ids;
    public $opinion_ids;
    public $event_ids;


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
            [['created_at', 'is_key_topic', 'sticky_at', 'article_ids', 'video_ids', 'opinion_ids', 'event_ids'], 'safe'],
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
            'video_ids' => Yii::t('app', 'Videos'),
            'opinion_ids' => Yii::t('app', 'Opinions'),
            'event_ids' => Yii::t('app', 'Events'),
        ];
    }
    
    public function loadAttributes()
    {
        $relatedArticles = $this->getTopicArticles()->all();
        
        foreach ($relatedArticles as $article) {
            $currentArticle = $article->article;
            $this->article_ids[] = $currentArticle->id; 
        }
        
        $relatedVideos = $this->getTopicVideos()->all();
        
        foreach ($relatedVideos as $video) {
            $currentVideo = $video->video;
            $this->video_ids[] = $currentVideo->id;
        }
        
        $relatedOpinions = $this->getTopicOpinions()->all();
        
        foreach ($relatedOpinions as $opinion) {
            $currentOpinion = $opinion->opinion;
            $this->opinion_ids[] = $currentOpinion->id;
        }
        
        $relatedEvents = $this->getTopicEvents()->all();
        
        foreach ($relatedEvents as $event) {
            $currentEvent = $event->event;
            $this->event_ids[] = $currentEvent->id;
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
        $articles = Article::find()->orderBy('id desc')->all();
        $articlesList = [];
        foreach ($articles as $article) {
            $articlesList[$article->id] = $article->title; 
        }
        return $articlesList;
    }
    
    
    public function videosList()
    {
        $videos = Video::find()->orderBy('id desc')->all();
        $videosList = [];
        foreach ($videos as $video) {
            $videosList[$video->id] = $video->title; 
        }
        return $videosList;
    }
    
    public function opinionsList()
    {
        $opinions = Opinion::find()->orderBy('id desc')->all();
        $opinionsList = [];
        foreach ($opinions as $opinion) {
            $opinionsList[$opinion->id] = $opinion->title; 
        }
        return $opinionsList;
    }
    
    public function eventsList()
    {
        $events = Event::find()->orderBy('id desc')->all();
        $eventsList = [];
        foreach ($events as $event) {
            $eventsList[$event->id] = $event->title; 
        }
        return $eventsList;
    }
    
    public function getTopicArticles()
    {
        return $this->hasMany(TopicArticle::className(), ['topic_id' => 'id']);
    }
    
    public function getTopicVideos()
    {
        return $this->hasMany(TopicVideo::className(), ['topic_id' => 'id']);
    }
    
    public function getTopicOpinions()
    {
        return $this->hasMany(TopicOpinion::className(), ['topic_id' => 'id']);
    }
    
    public function getTopicEvents()
    {
        return $this->hasMany(TopicEvent::className(), ['topic_id' => 'id']);
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
        $this->saveVideosList();
        $this->saveOpinionsList();
        $this->saveEventsList();
        
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
    
    protected function saveVideosList()
    {
        TopicVideo::deleteAll(['=', 'topic_id', $this->id]);
        
        $bulkInsertArray = [];
        
        if (is_array($this->video_ids)) {
            
            foreach ($this->video_ids as $id) {
                $bulkInsertArray[]=[
                    'topic_id' => $this->id,
                    'video_id' => $id,
                ];
            }

            if (count($bulkInsertArray) > 0){
                $columnNamesArray = ['topic_id', 'video_id'];
                $insertCount = Yii::$app->db->createCommand()
                               ->batchInsert(
                                       TopicVideo::tableName(), $columnNamesArray, $bulkInsertArray
                                 )
                               ->execute();
            }
        }
    }
    
    protected function saveOpinionsList()
    {
        TopicOpinion::deleteAll(['=', 'topic_id', $this->id]);
        
        $bulkInsertArray = [];
        
        if (is_array($this->opinion_ids)) {
            
            foreach ($this->opinion_ids as $id) {
                $bulkInsertArray[]=[
                    'topic_id' => $this->id,
                    'opinion_id' => $id,
                ];
            }

            if (count($bulkInsertArray) > 0){
                $columnNamesArray = ['topic_id', 'opinion_id'];
                $insertCount = Yii::$app->db->createCommand()
                               ->batchInsert(
                                       TopicOpinion::tableName(), $columnNamesArray, $bulkInsertArray
                                 )
                               ->execute();
            }
        }
    }
    
    protected function saveEventsList()
    {
        TopicEvent::deleteAll(['=', 'topic_id', $this->id]);
        
        $bulkInsertArray = [];
        
        if (is_array($this->event_ids)) {
            
            foreach ($this->event_ids as $id) {
                $bulkInsertArray[]=[
                    'topic_id' => $this->id,
                    'event_id' => $id,
                ];
            }

            if (count($bulkInsertArray) > 0){
                $columnNamesArray = ['topic_id', 'event_id'];
                $insertCount = Yii::$app->db->createCommand()
                               ->batchInsert(
                                       TopicEvent::tableName(), $columnNamesArray, $bulkInsertArray
                                 )
                               ->execute();
            }
        }
    }

}
