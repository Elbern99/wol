<?php
namespace backend\modules\parser;

use common\contracts\ReaderInterface;
use ZipArchive;
use yii\helpers\FileHelper;
use Yii;

class Reader implements ReaderInterface {
    
    private $images = [];
    private $xml;
    private $pdf = [];
    private $temporaryFolder;
    
    public function read($file) {

        if (file_exists($file)) {
            
            $zip = new ZipArchive;

            if ($zip->open($file, ZipArchive::CREATE) === true) {
                
                $zipId = Yii::$app->getSecurity()->generateRandomString(9);
                $this->temporaryFolder = Yii::getAlias('@backend').'/runtime/temporary_folder/'.$zipId;
                
                if (!is_dir($this->temporaryFolder)) {
                    FileHelper::createDirectory($this->temporaryFolder, 0777, true);
                }
                
                $zip->extractTo($this->temporaryFolder);
                $zip->close();
                
                $dh  = opendir($this->temporaryFolder);
                
                while (false !== ($filename = readdir($dh))) {
                    
                    $filePath = $this->temporaryFolder.'/'.$filename;
                    $type = mime_content_type($filePath);

                    if (preg_match("/(jpg|jpeg|png|gif)$/i", $type)) {
                        $this->images[$filename] = $filePath;
                    } elseif (preg_match("/pdf$/i", $type)) {
                        $this->pdf[$filename] = $filePath;
                    } elseif (preg_match("/xml$/i", $type)) {
                        $this->xml = $filePath;
                    }
                
                }
                
            }

        }
        
        return $this;
    }
    
    public function getImages() {
        return $this->images;
    }
    
    public function getPdfs() {
        return $this->pdf;
    }
    
    public function getXml() {
        return $this->xml;
    }
    
    public function removeTemporaryFolder() {
        
        $path = $this->temporaryFolder;
        $this->temporaryFolder = null;
        
        if (is_dir($path) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true) {
                        rmdir($file->getPathName());
                    } else if (($file->isFile() === true) || ($file->isLink() === true)) {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        } else if ((is_file($path) === true) || (is_link($path) === true)) {
            return unlink($path);
        }

        return false;
        
    }
}

