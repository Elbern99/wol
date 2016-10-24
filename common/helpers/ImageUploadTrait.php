<?php
namespace \common\helpers;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

trait ImageUploadTrait {
    
    protected $images = [];
    protected $mode = 0775;
    
    abstract public function getFrontendPath();
    abstract public function getBackendPath();
    
    //before load
    
    public function initUploadProperty() {
        
        $load = false;
        foreach ($this->images as $image) {
            
            $upload = UploadedFile::getInstance($this, $image);
            
            if (is_object($upload) && $upload->name) {
                $this->$image = $upload;
                $load = true;
            }
        }
        
        return $load;
    }
    
    public function upload() {
        
        try {
            
            foreach ($this->images as $image) {

                if (is_object($this->$image)) {

                    $imageName = Yii::$app->getSecurity()->generateRandomString(9);
                    $imageName .= '.' . $this->$image->extension;
                    $this->$image->name = $imageName;
                    $this->saveImage($this->$image, $this->getBackendPath());
                    $this->saveImage($this->$image, $this->getFrontendPath());
                }
            }
            
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
    
    protected function saveImageFrontend($image, $path) {

        if ($path) {
            
            if (!file_exists($path)) {
                if (!FileHelper::createDirectory($path, $this->mode, true)) {
                    return false;
                }
            }
            
            $image->saveAs($path);
        }

    }

}

