<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms_pages_widget".
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $widget_id
 *
 * @property CmsPages $page
 * @property Widget $widget
 */
class CmsPagesWidget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_pages_widget';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'widget_id'], 'required'],
            [['page_id', 'widget_id'], 'integer'],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsPages::className(), 'targetAttribute' => ['page_id' => 'id']],
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
            'page_id' => Yii::t('app', 'Page ID'),
            'widget_id' => Yii::t('app', 'Widget ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(CmsPages::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidget()
    {
        return $this->hasOne(Widget::className(), ['id' => 'widget_id']);
    }
    
    public function getPageWidgets($id) {
        return $this->find()
                    ->alias('wp')
                    ->where(['wp.page_id'=>$id])
                    ->innerJoin(['widget' => Widget::tableName()], 'wp.widget_id = widget.id')
                    ->select(['widget.text', 'widget.name'])
                    ->asArray()
                    ->all();
    }
}
