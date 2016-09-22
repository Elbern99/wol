<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bottom_menu".
 *
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property string $class
 * @property integer $order
 * @property integer $enabled
 */
class BottomMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bottom_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'order'], 'required'],
            [['order', 'enabled'], 'integer'],
            [['title', 'link', 'class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'link' => Yii::t('app', 'Link'),
            'class' => Yii::t('app', 'Class'),
            'order' => Yii::t('app', 'Order'),
            'enabled' => Yii::t('app', 'Enabled'),
        ];
    }
    
    /*
     * Get bottom menu item query 
     * @return object
     */
    public static function getVisibleItemsQuery() {
        return self::find()->where(['enabled' => 1])->orderBy(['order'=>SORT_ASC]);
    }
}
