<?php

namespace common\models;

use Yii;


class NewsArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'news_id'], 'required'],
            [['article_id', 'news_id'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsItem::className(), 'targetAttribute' => ['news_id' => 'id']],
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
            'news_id' => Yii::t('app', 'News Item ID'),
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
    public function getNewsItem()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }
    

    public static function massInsert(array $bulkInsertArray) {

        if (count($bulkInsertArray)) {

            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        self::tableName(), ['article_id', 'news_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        }

        return false;
    }

}
