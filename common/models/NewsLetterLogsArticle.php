<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class NewsLetterLogsArticle
 * @package common\models
 *
 * @property integer $article_id
 * @property integer $newsletter_logs_id
 *
 * @property NewsletterLogs $log
 * @property Newsletter[] $subscribers
 */
class NewsLetterLogsArticle extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter_logs_article}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => Yii::t('app', 'Article ID'),
            'newsletter_logs_id' => Yii::t('app', 'Newsletter Logs ID')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'newsletter_logs_id'], 'required'],
            [['article_id', 'newsletter_logs_id'], 'integer'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLog()
    {
        return $this->hasOne(NewsletterLogs::class, ['id' => 'newsletter_logs_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubscribers()
    {
        return $this->hasMany(Newsletter::class, ['id' => 'newsletter_id'])
            ->viaTable('newsletter_logs_users', ['newsletter_logs_id' => 'id']);
    }
}
