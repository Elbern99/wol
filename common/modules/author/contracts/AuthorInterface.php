<?php
namespace common\modules\author\contracts;

interface AuthorInterface {
    public function addNewAuthor($args);
    public function getFrontendImagesBasePath();
    public function getBackendImagesBasePath();
    
}
