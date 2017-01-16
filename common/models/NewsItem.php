<?php

namespace common\models;

use Yii;

class NewsItem extends \yii\db\ActiveRecord
{
    use \common\helpers\FileUploadTrait;
    
    protected $files = [
        'image_link',
    ];
    
    protected $imagePath = '/web/uploads/news';


    public function getImagePath()
    {
        if ($this->image_link) {
            return Yii::getAlias('@frontend') . $this->imagePath . $this->image_link;
        }
  
        return null;
    }
    
    public function getFrontendPath() {
        // return Yii::getAlias('@frontend').'/web/uploads/news';
        return Yii::getAlias('@frontend') . $this->imagePath;
    }
    
    public function getBackendPath() {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title', 'editor'], 'required'],
            [['description', 'short_description'], 'string'],
            [['created_at'], 'safe'],
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
            'editor' => Yii::t('app', 'Sources'),
            'description' => Yii::t('app', 'Description'),
            'short_decription' => Yii::t('app', 'Short Description'),
            'image_link' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
    
    public function afterFind()
    {
        $this->created_at = new \DateTime($this->created_at);
    }
    
    public function saveFormatted()
    {
        if (!$this->validate())
            return false;

        $this->setCreatedAtDate();
        $this->initUploadProperty();
        $this->upload();
        
        return $this->save();
    }
    
    protected function setCreatedAtDate()
    {
        $date = $this->created_at ? $this->created_at : 'now';
        
        try {
            $created_at = new \DateTime($date);
        } catch (\Exception $e) {
            $created_at = new \DateTime('now');
        }
        $this->created_at = $created_at->format('Y-m-d');
    }

}
