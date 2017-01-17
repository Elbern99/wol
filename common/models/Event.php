<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

class Event extends \yii\db\ActiveRecord
{
    use \common\helpers\FileUploadTrait;
    
    protected $files = [
        'image_link',
    ];
    
    protected $imagePath = '/web/uploads/events';


    public function getImagePath()
    {
        if ($this->image_link) {
            return Yii::getAlias('@frontend') . $this->imagePath . $this->image_link;
        }
  
        return null;
    }
    
    public function getFrontendPath() {
        return Yii::getAlias('@frontend').'/web/uploads/events';
    }
    
    public function getBackendPath() {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title', 'location', 'date_from', 'date_to', 'book_link', 'contact_link'], 'required'],
            [['body', 'short_description'], 'string'],
            [['url_key'], 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['title'], 'string', 'max' => 255],
            [['url_key'], 'unique'],
            [['image_link'], 'file', 'extensions' => 'jpg, gif, png, bmp, jpeg, jepg', 'skipOnEmpty' => true],
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
            'body' => Yii::t('app', 'Description'),
            'date_from' => Yii::t('app', 'Date From'),
            'date_to' => Yii::t('app', 'Date To'),
            'location' => Yii::t('app', 'Location'),
            'short_decription' => Yii::t('app', 'Short Description'),
            'book_link' => Yii::t('app', 'Book Link'),
            'contact_link' => Yii::t('app', 'Contact Link'),
            'image_link' => Yii::t('app', 'Image'),
        ];
    }
    
    public function afterFind()
    {
        $this->date_from = new \DateTime($this->date_from);
        $this->date_to = new \DateTime($this->date_to);
    }
 
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->checkImageLink();
            return true;
        } else {
            return false;
        }
        
    }
    
    public function checkImageLink()
    {
        $image =  UploadedFile::getInstance($this, 'image_link');
        
        if (!$image) {
            $currentItem = self::find()->where(['id' => $this->id])->one();
            if ($currentItem && $currentItem->image_link) {
                $this->image_link = $currentItem->image_link;
            }
        }
    }
    
    public function saveFormatted()
    {
        if (!$this->validate())
            return false;
        
        $this->convertDates();
        $this->initUploadProperty();
        $this->upload();
        
        return $this->save();
    }
    
    protected function convertDates()
    {
        $date_from = new \DateTime($this->date_from);
        $this->date_from = $date_from->format('Y-m-d');
        $date_to = new \DateTime($this->date_to);
        $this->date_to = $date_to->format('Y-m-d');
    }

}
