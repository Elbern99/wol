<?php

namespace common\helpers;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

trait ImageUploadTrait {
    
    protected $mode = 0775;
    
    abstract public function getFrontendPath();
    abstract public function getBackendPath();
    
    
    public function initUploadProperty() {

        foreach ($this->images as $image) {
            
            $upload = UploadedFile::getInstance($this, $image);
            
            if (is_object($upload) && $upload->name) {
                
                $this->setAttribute($image,$upload);
            }
        }
        
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
    
    protected function saveImage($image, $path) {

        if ($path) {

            if (!file_exists($path)) {

                if (!FileHelper::createDirectory($path, $this->mode, true)) {
                    return false;
                }
                
            }

            $imageName = $path.'/'.$image->name;
            $image->saveAs($imageName);
        }

    }

}

