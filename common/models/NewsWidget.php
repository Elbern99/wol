<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news_widget".
 *
 * @property integer $id
 * @property integer $news_id
 * @property integer $widget_id
 * @property integer $order
 *
 * @property News $news
 * @property Widget $widget
 */
class NewsWidget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_widget';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'widget_id'], 'required'],
            [['news_id', 'widget_id', 'order'], 'integer'],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsItem::className(), 'targetAttribute' => ['news_id' => 'id']],
            [['widget_id'], 'exist', 'skipOnError' => true, 'targetClass' => Widget::className(), 'targetAttribute' => ['widget_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'news_id' => Yii::t('app', 'News ID'),
            'widget_id' => Yii::t('app', 'Widget ID'),
            'order' => Yii::t('app', 'Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidget()
    {
        return $this->hasOne(Widget::className(), ['id' => 'widget_id']);
    }
    
    public static function getPageWidgets($id) {
        return self::find()
                    ->alias('wp')
                    ->where(['wp.news_id'=>$id])
                    ->innerJoin(['widget' => Widget::tableName()], 'wp.widget_id = widget.id')
                    ->select(['widget.text', 'widget.name'])
                    ->orderBy('wp.order')
                    ->asArray()
                    ->all();
    }
}
