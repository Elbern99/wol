<?php
namespace backend\modules\parser\contracts;

interface UploadInterface {
    public function getTypeParse();
    public function getArchivePath();
    public function getActionClass();
}

