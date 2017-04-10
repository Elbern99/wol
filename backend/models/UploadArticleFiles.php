<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class UploadArticleFiles extends Model
{
    
    const FILE_PDF_OPTION = 'pdf';
    const FILE_IMAGE_OPTION = 'image';
    
    public $file;
    public $filename;
    public $type;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'type'], 'required'],
            [['file'], 'safe'],
            [['filename', 'type'], 'string'],
            [['file'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true, 'maxFiles' => 5],
        ];
    }

    public function getTypeOptions() {
        return [self::FILE_PDF_OPTION => 'Pdf', self::FILE_IMAGE_OPTION => 'Image'];
    }

    public function upload() {
        
        if ($this->validate()) {
            $result = [];
            
            foreach ($this->editor_images as $image) {
                
                $imageName = Yii::$app->getSecurity()->generateRandomString(9);
                $imageName .= '.' . $image->extension;

                $filePath = Yii::getAlias('@frontend') . '/web/uploads/' . self::IMAGE_PATH . '/' . $imageName;

                if ($image->saveAs($filePath)) {

                    $result[] = Url::to('uploads/' . self::IMAGE_PATH . '/' . $imageName, true);
                }
            }
            
            return $result;
        }
        
        return false;
    }

}
