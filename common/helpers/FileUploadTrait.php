<?php

namespace common\helpers;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

trait FileUploadTrait {
    
    protected $mode = 0775;
    
    abstract public function getFrontendPath();
    abstract public function getBackendPath();
    
    
    public function initUploadProperty() {

        foreach ($this->files as $image) {
            
            $upload = UploadedFile::getInstance($this, $image);
            
            if (is_object($upload) && $upload->name) {
                
                $this->$image = $upload;
            }
        }
        
    }
    
    public function upload($saveName = false) {
        
        try {
            
            if ($this->validate()) {
                
                foreach ($this->files as $file) {

                    if (is_object($this->$file)) {
                        
                        if (!$saveName) {
                            
                            $fileName = Yii::$app->getSecurity()->generateRandomString(9);
                            $fileName .= '.' . $this->$file->extension;
                            $this->$file->name = $fileName;
                        }

                        $this->saveFile($this->$file, $this->getFrontendPath());
                        $this->saveFile($this->$file, $this->getBackendPath());
                    }
                }

                return true;
            }
            
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        
        return false;
    }
    
    protected function saveFile($file, $path) {

        if ($path) {

            if (!is_dir($path)) {

                if (!FileHelper::createDirectory($path, $this->mode, true)) {
                    return false;
                }
            }
            
            $fileName = $path . '/' . $file->name;
            $file->saveAs($fileName, false);
            return true;
        }
        
        return false;
    }

}

