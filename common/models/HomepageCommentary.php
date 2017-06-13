<?php

namespace common\models;

use common\models\Video;

use Yii;

class HomepageCommentary extends \yii\db\ActiveRecord
{
    public $video_ids = [];
    public $opinion_ids = [];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'homepage_commentary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_ids', 'opinion_ids'], 'safe'],
        ];
    }
    
    public function loadAttributes()
    {
        $objects = HomepageCommentary::find()->all();

        foreach ($objects as $object) {
            if ($object->type == Video::class) {
                $this->video_ids[] = $object->object_id;
            } else if ($object->type == Opinion::class) {
                $this->opinion_ids[] = $object->object_id;
            }
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
    
    public static function opinionsList()
    {
        $opinions = Opinion::find()->where(['enabled' => 1])->all();
        $opinionsList = [];
        foreach ($opinions as $opinion) {
            $opinionsList[$opinion->id] = $opinion->title; 
        }
        return $opinionsList;
    }
   
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'video_ids' => Yii::t('app', 'Videos'),
            'opinion_ids' => Yii::t('app', 'Opinions '),
        ];
    }
    
    public function saveData()
    {
        $this->deleteAll();
        $videoType = Video::class;
        $opinionType = Opinion::class;
        
        $this->saveList($this->video_ids, $videoType);
        $this->saveList($this->opinion_ids, $opinionType);

        return true;
    }
    
    protected function saveList($list = [], $type = null)
    {
        
        $bulkInsertArray = [];
        
        if (is_array($list)) {
            
            foreach ($list as $id) {
                $bulkInsertArray[]=[
                    'object_id' => $id,
                    'type' => $type,
                ];
            }

            if (count($bulkInsertArray) > 0){
                $columnNamesArray = ['object_id', 'type'];
                $insertCount = Yii::$app->db->createCommand()
                               ->batchInsert(
                                    HomepageCommentary::tableName(), $columnNamesArray, $bulkInsertArray
                                )
                               ->execute();
            }
        }
    }
}
