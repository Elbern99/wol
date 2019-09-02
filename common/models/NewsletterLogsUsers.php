<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * Class NewsletterLogsUsers
 * @package common\models
 *
 * @property integer $newsletter_id
 * @property integer $newsletter_logs_id
 *
 * @property NewsletterLogs $log
 * @property Newsletter[] $subscribers
 */
class NewsletterLogsUsers extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter_logs_users}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newsletter_id' => Yii::t('app', 'Newsletter ID'),
            'newsletter_logs_id' => Yii::t('app', 'Newsletter Logs ID')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsletter_id', 'newsletter_logs_id'], 'required'],
            [['newsletter_id', 'newsletter_logs_id'], 'integer'],
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
