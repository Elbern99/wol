<?php
namespace backend\modules\parser\contracts;

/*
 * interface for upload object
 */
interface UploadInterface {
    public function getTypeParse();
    public function getArchivePath();
    public function getActionClass();
}

