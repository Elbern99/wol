<?php

namespace common\models;

use Yii;


class TopicEvent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic_events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'topic_id'], 'required'],
            [['event_id', 'topic_id'], 'integer'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
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
            'event_id' => Yii::t('app', 'Event ID'),
            'topic_id' => Yii::t('app', 'Topic ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
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
                        self::tableName(), ['event_id', 'topic_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        }

        return false;
    }

}
