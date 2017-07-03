<?php
namespace frontend\components\image;

use yii\imagine\BaseImage;
use Yii;
use Imagine\Image\ManipulatorInterface;
use yii\helpers\FileHelper;
use yii\imagine\Image;

class ImageCache extends BaseImage {
    
    protected $mode = 0775;

    public function getImgSrc($originalPath, $width, $height) {
        
        $path = str_replace(Yii::$app->imageCache->baseFolder, '', $originalPath);
        $basePath = Yii::getAlias('@frontend').'/web'.Yii::$app->imageCache->baseFolder.$path;
        $cacheFolder = Yii::getAlias('@frontend').'/web'.Yii::$app->imageCache->cacheFolder;
        $cacheBasePath = $cacheFolder.'/'.$path;
        $cacheUrl = Yii::$app->imageCache->cacheFolder.'/'.$path;
        
        if (!file_exists($cacheBasePath)) {
            
            if (file_exists($basePath)) {
                
                $folders = explode('/',$path);
                
                if (isset($folders[0])) {
                    $cacheFolder .= '/'.$folders[0];
                }

                if (!is_dir($cacheFolder)) {
                    
                    if (!$this->createCacheFolder($cacheFolder)) {
                        return $originalPath;
                    }
                }
                
                if ($this->createThumb($basePath, $cacheBasePath, $width, $height)) {
                    return $cacheUrl;
                }
            }
            
            return $originalPath;
        }
        
        return $cacheUrl;
    }
    
    protected function createThumb($srcPath, $dstPath, $width, $height, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND) {
        
        $thumb = Image::thumbnail($srcPath, $width, $height, $mode);

        if ($thumb && $thumb->save($dstPath)) {
            return true;
        }

        return false;
    }
    
    protected function createCacheFolder($folder) {
        
        if (FileHelper::createDirectory($folder, $this->mode, true)) {
            return true;
        }
        
        return false;
    }
}
