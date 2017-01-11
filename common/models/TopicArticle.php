<?php

namespace common\models;

use Yii;


class TopicArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'topic_articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'topic_id'], 'required'],
            [['article_id', 'topic_id'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
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
            'article_id' => Yii::t('app', 'Article ID'),
            'topic_id' => Yii::t('app', 'Topic ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
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
                        self::tableName(), ['article_id', 'topic_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        }

        return false;
    }

}
