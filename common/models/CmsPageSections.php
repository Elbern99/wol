<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms_page_sections".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $title
 * @property string $anchor
 * @property integer $open
 * @property integer $order
 * @property string $text
 *
 * @property CmsPages $page
 */
class CmsPageSections extends \yii\db\ActiveRecord implements \common\contracts\CmsPageTypeInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_page_sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'title', 'anchor', 'order', 'text'], 'required'],
            [['page_id', 'open', 'order'], 'integer'],
            [['text'], 'string'],
            ['anchor', 'match', 'pattern' => '/^[a-z0-9_-]+$/'],
            [['title'], 'string', 'max' => 255],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsPages::className(), 'targetAttribute' => ['page_id' => 'id']],
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
            'title' => Yii::t('app', 'Title'),
            'anchor' => Yii::t('app', 'Anchor'),
            'open' => Yii::t('app', 'Open'),
            'order' => Yii::t('app', 'Order'),
            'text' => Yii::t('app', 'Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(CmsPages::className(), ['id' => 'page_id']);
    }
    
    public function getContents($id) {

        return $this->find()
                ->where(['page_id' => $id])
                ->select(['title', 'anchor', 'open', 'text'])
                ->orderBy(['order' => SORT_ASC])
                ->asArray()
                ->all();
    }
            
}
