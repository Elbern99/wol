<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

class PressReleaseItem extends \yii\db\ActiveRecord
{
    use \common\helpers\FileUploadTrait;
    
    protected $files = [
        'image_link',
        'pdf_link'
    ];
    
    protected $imagePath = '/web/uploads/press-releases';

    public function getImagePath()
    {
        if ($this->image_link) {
            return Yii::getAlias('@frontend') . $this->imagePath . '/' . $this->image_link;
        }
  
        return null;
    }
    
    public function getPdfPath()
    {
        if ($this->image_link) {
            return Yii::getAlias('@frontend') . $this->imagePath . '/' . $this->pdf_link;
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
        return 'press_releases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title', ], 'required'],
            [['description', 'short_description'], 'string'],
            [['created_at',], 'safe'],
            [['url_key'], 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['title'], 'string', 'max' => 255],
            [['url_key'], 'unique'],
            [['image_link'], 'file', 'extensions' => 'jpg, gif, png, bmp, jpeg, jepg', 'skipOnEmpty' => true],
            [['pdf_link'], 'file', 'extensions' => 'pdf', 'skipOnEmpty' => true],
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
            'description' => Yii::t('app', 'Description'),
            'short_decription' => Yii::t('app', 'Short Description'),
            'image_link' => Yii::t('app', 'Image'),
            'pdf_link' => Yii::t('app', 'PDF'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
    
    public function afterFind()
    {
        $this->created_at = new \DateTime($this->created_at);
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->checkImageLink();
            $this->checkPdfLink();
            return true;
        } else {
            return false;
        }   
    }
    
    public function deleteImage()
    {
        if ($this->image_link) {
            if (file_exists($this->getImagePath())) {
                unlink($this->getImagePath());
            }
            
            Yii::$app->db->createCommand()
            ->update(self::tableName(), ['image_link' => ''], 'id = ' . $this->id)
            ->execute();
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
    
    public function checkPdfLink()
    {
        $pdf =  UploadedFile::getInstance($this, 'image_link');
        
        if (!$pdf) {
            $currentItem = self::find()->where(['id' => $this->id])->one();
            if ($currentItem && $currentItem->pdf_link) {
                $this->pdf_link = $currentItem->pdf_link;
            }
        }
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
