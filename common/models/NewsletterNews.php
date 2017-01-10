<?php

namespace common\models;

use Yii;
use common\components\DateBehavior;
/**
 * This is the model class for table "newsletter_news".
 *
 * @property integer $id
 * @property string $url_key
 * @property integer $date
 * @property string $title
 * @property string $main
 */
class NewsletterNews extends \yii\db\ActiveRecord
{
    
    public function behaviors() {
        return [
            DateBehavior::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newsletter_news';
    }
    
    public function beforeSave($insert) {
        
        if (parent::beforeSave($insert)) {
            $this->date = strtotime($this->date);
            $this->url_key = date('Y/m',$this->date);
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'title', 'main'], 'required'],
            [['main'], 'string'],
            [['url_key', 'title'], 'string', 'max' => 255],
            [['url_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url_key' => Yii::t('app', 'Url Key'),
            'date' => Yii::t('app', 'Date'),
            'title' => Yii::t('app', 'Title'),
            'main' => Yii::t('app', 'Main'),
        ];
    }
}
