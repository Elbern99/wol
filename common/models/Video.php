<?php

namespace common\models;

use Yii;
use common\contracts\VideoInterface;

/**
 * This is the model class for table "video".
 *
 * @property integer $id
 * @property string $url_key
 * @property string $title
 * @property string $video
 * @property string $image
 * @property string $description
 * @property integer $order
 */
class Video extends \yii\db\ActiveRecord implements VideoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title', 'video'], 'required'],
            [['description'], 'string'],
            [['order'], 'integer'],
            [['url_key'], 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['video'], 'url'],
            [['title'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 60],
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
            'title' => Yii::t('app', 'Title'),
            'video' => Yii::t('app', 'Video'),
            'image' => Yii::t('app', 'Image'),
            'description' => Yii::t('app', 'Description'),
            'order' => Yii::t('app', 'Order'),
        ];
    }
    
    public function getVideo() {
        return $this->getAttribute('video');
    }
}
