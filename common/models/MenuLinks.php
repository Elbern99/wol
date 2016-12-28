<?php

namespace common\models;

use Yii;
use common\contracts\TypeInterface;
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
class MenuLinks extends \yii\db\ActiveRecord
{
    const TOP_LINK = 1;
    const BOTTOM_LINK = 3;
    
    private $params = [
        self::TOP_LINK => 'Top Menu',
        self::BOTTOM_LINK => 'Bottom Menu'
    ];
    
    private $type = null;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_links';
    }
    
    public function init()
    {
        parent::init();
        
        $this->type = Yii::createObject(TypeInterface::class);
        $this->type->addTypes($this->params);
    }
    
    public function getType() {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'order', 'type'], 'required'],
            [['order', 'enabled', 'type'], 'integer'],
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
            'type' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'link' => Yii::t('app', 'Link'),
            'class' => Yii::t('app', 'Class'),
            'order' => Yii::t('app', 'Order'),
            'enabled' => Yii::t('app', 'Enabled'),
        ];
    }
    
}
