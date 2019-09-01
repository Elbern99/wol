<?php

namespace common\models;

use common\components\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class NewsletterLogs
 * @package common\models
 *
 * @property integer $id
 * @property string $subject
 * @property integer $qty
 * @property string $error_text
 * @property integer $status
 * @property integer $progress
 * @property integer $created_at
 * @property integer $updated_at
 */
class NewsletterLogs extends ActiveRecord
{
    const STATUS_WARNING = 4;
    const STATUS_ERROR = 3;
    const STATUS_SUCCESS = 2;
    const STATUS_IN_PROGRESS = 1;

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'newsletter_logs';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'qty' => Yii::t('app', 'Quantity'),
            'error_text' => Yii::t('app', 'Error'),
            'status' => Yii::t('app', 'Status'),
            'progress' => Yii::t('app', 'Progress'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['subject', 'qty', 'status', 'progress'], 'required'],
            [['qty', 'status', 'created_at', 'updated_at'], 'integer'],
            ['subject', 'string', 'max' => 255],
            ['error_text', 'string', 'min' => 0],
            ['progress', 'integer', 'min' => 1, 'max' => 100],
            ['status', 'in', 'range' => array_keys(self::getStatuses())]
        ];
    }

    /**
     * Get list of statuses
     *
     * @return array where key is value and value is name of status
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_IN_PROGRESS => Yii::t('app', 'In progress'),
            self::STATUS_SUCCESS => Yii::t('app', 'Completed'),
            self::STATUS_ERROR => Yii::t('app', 'Error'),
            self::STATUS_WARNING => Yii::t('app', 'Warning'),
        ];
    }
}
