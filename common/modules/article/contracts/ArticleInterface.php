<?php
namespace common\modules\article\contracts;

interface ArticleInterface {
    
    public function getFrontendImagesBasePath();
    public function getBackendImagesBasePath();
    public function getFrontendPdfsBasePath();
    public function getBackendPdfsBasePath();
    
}
