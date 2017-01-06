<?php

namespace common\models;

use common\models\Video;

use Yii;

class RelatedVideo extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video_relations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'children_id'], 'safe'],
        ];
    }
    
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['id' => 'children_id']);
    }
}
