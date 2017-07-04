<?php

namespace common\models;

use common\models\Video;
use yii\helpers\ArrayHelper;
use Yii;

class CommentaryVideo extends \yii\db\ActiveRecord
{
    public $video_ids = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'commentary_videos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_ids',], 'safe'],
        ];
    }
    
    public function loadAttributes()
    {
        $commentaryVideos = CommentaryVideo::find()->select('video_id')->all();
        foreach ($commentaryVideos as $video) {
            $currentVideo = Video::find()->where(['=', 'id', $video->video_id])->one();
            $this->video_ids[] = $currentVideo->id; 
        }
    }
    
    public static function videosList()
    {
        $videos = Video::find()->all();
        $videosList = [];
        foreach ($videos as $video) {
            $videosList[$video->id] = $video->title; 
        }
        return $videosList;
    }
    
    public static function videosListIds()
    {
        $commentaryVideos = CommentaryVideo::find()->select('video_id')->asArray()->all();
        return ArrayHelper::getColumn($commentaryVideos, 'video_id');
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'video_ids' => Yii::t('app', 'Videos'),
        ];
    }
    
    public function saveData()
    {
        $this->deleteAll();
        $bulkInsertArray = [];
        
        if (is_array($this->video_ids)) {
            
            foreach ($this->video_ids as $id) {
                $bulkInsertArray[]=[
                    'video_id' => $id,
                ];
            }

            if (count($bulkInsertArray)>0){
                $columnNamesArray = ['video_id'];
                $insertCount = Yii::$app->db->createCommand()
                               ->batchInsert(
                                     self::tableName(), $columnNamesArray, $bulkInsertArray
                                 )
                               ->execute();
            }
        }
        
        return true;
    }
}
