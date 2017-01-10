<?php

namespace common\models;

use Yii;


class TopicOpinion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic_opinions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opinion_id', 'topic_id'], 'required'],
            [['opinion_id', 'topic_id'], 'integer'],
            [['opinion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Opinion::className(), 'targetAttribute' => ['opinion_id' => 'id']],
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
            'opinion_id' => Yii::t('app', 'Opinion ID'),
            'topic_id' => Yii::t('app', 'Topic ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpinion()
    {
        return $this->hasOne(Opinion::className(), ['id' => 'opinion_id']);
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
                        self::tableName(), ['opinion_id', 'topic_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        }

        return false;
    }

}
