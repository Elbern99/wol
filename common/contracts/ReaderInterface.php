<?php
namespace common\contracts;

interface ReaderInterface {
    public function read($file);
    public function getXml();
    public function getPdfs();
    public function getImages();
    public function removeTemporaryFolder();
}

