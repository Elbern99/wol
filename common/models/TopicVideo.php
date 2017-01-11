<?php

namespace common\models;

use Yii;


class TopicVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic_videos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id', 'topic_id'], 'required'],
            [['video_id', 'topic_id'], 'integer'],
            [['video_id'], 'exist', 'skipOnError' => true, 'targetClass' => Video::className(), 'targetAttribute' => ['video_id' => 'id']],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topic::className(), 'targetAttribute' => ['topic_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'video_id' => Yii::t('app', 'Video ID'),
            'topic_id' => Yii::t('app', 'Topic ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['id' => 'video_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }
    

    public static function massInsert(array $bulkInsertArray) {

        if (count($bulkInsertArray)) {

            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        self::tableName(), ['video_id', 'topic_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        }

        return false;
    }

}
