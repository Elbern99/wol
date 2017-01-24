<?php

namespace common\models;

use Yii;
use common\contracts\VideoInterface;
use common\helpers\VideoHelper;
use common\models\Video;

class Video extends \yii\db\ActiveRecord implements VideoInterface
{
    public $video_ids;
    //public $category_id;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title', 'video'], 'required'],
            [['description'], 'string'],
            [['order'], 'integer'],
            [['url_key'], 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['video'], 'url'],
            [['title'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 60],
            [['url_key'], 'unique'],
            [['video_ids', 'category_id',], 'safe'],
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
            'video' => Yii::t('app', 'Video'),
            'image' => Yii::t('app', 'Image'),
            'description' => Yii::t('app', 'Description'),
            'order' => Yii::t('app', 'Order'),
            'video_ids' => Yii::t('app', 'Related Videos'),
            'category_id' => Yii::t('app', 'Category'),
        ];
    }
    
    public function loadAttributes()
    {
        $childVideos = RelatedVideo::find()->select('children_id')->where(['=', 'parent_id', $this->id])->all();
        foreach ($childVideos as $video) {
            $currentVideo = Video::find()->where(['=', 'id', $video->children_id])->one();
            $this->video_ids[] = $currentVideo->id; 
        }
    }
    
    public function getVideo() {
        return $this->getAttribute('video');
    }
    
    public function getImageLink() {
        VideoHelper::load($this);
        return VideoHelper::getImage();
    }
        
    private function getVideoId() 
    {
        $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
        preg_match($pattern, $this->video, $matches);
        return (isset($matches[1])) ? $matches[1] : false;
    }
    
    
    public function getVideoImageLink() 
    {
        return 'http://img.youtube.com/vi/' . $this->getVideoId() . '/hqdefault.jpg';
    }
    
    public function getRelatedVideos()
    {
        return $this->hasMany(RelatedVideo::className(), ['parent_id' => 'id']);
    }
    
    
    public function videosList()
    {
        if ($this->id) {
            $videos = Video::find()->where(['<>', 'id', $this->id])->all();
        } else {
            $videos = Video::find()->all();
        }
        $videosList = [];
        foreach ($videos as $video) {
            $videosList[$video->id] = $video->title; 
        }
        return $videosList;
    }
    
    public function categoriesList() 
    {
        $categoriesList = [];
        $articleCategory = Category::find()->where(['url_key' => 'articles'])->one();
        
        if ($articleCategory) {
            $categories = Category::find()->where([
               'root' => $articleCategory->id,
               'lvl' => 1,
            ])->all();
            
            foreach ($categories as $category) {
                $categoriesList[$category->id] = $category->meta_title; 
            }
        }
        
        return $categoriesList;
    }
    
    public function saveData()
    {
        RelatedVideo::deleteAll(['=', 'parent_id', $this->id]);
        
        if ($this->save()) {
            $bulkInsertArray = [];

            if (is_array($this->video_ids)) {

                foreach ($this->video_ids as $id) {
                    $bulkInsertArray[]=[
                        'parent_id' => $this->id,
                        'children_id' => $id,
                    ];
                }

                if (count($bulkInsertArray)>0){
                    $columnNamesArray = ['parent_id', 'children_id'];
                    $insertCount = Yii::$app->db->createCommand()
                                   ->batchInsert(
                                        RelatedVideo::tableName(), $columnNamesArray, $bulkInsertArray
                                     )
                                   ->execute();
                }
            }
        }
        return true;
    }
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
