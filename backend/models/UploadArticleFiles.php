<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use common\modules\article\contracts\ArticleInterface;

class UploadArticleFiles extends Model
{
    use \common\helpers\FileUploadTrait;
    
    const FILE_PDF_OPTION = 'pdfs';
    const FILE_IMAGE_OPTION = 'images';
    
    private $article;
    protected $files = [
        'file'
    ];
    
    public $file;
    public $filename;
    public $type;

    public function __construct($config = array(), ArticleInterface $article) {
        $this->article = $article;
        parent::__construct($config);
    }
    
    public function getFrontendPath() {
        
        if ($this->type == self::FILE_PDF_OPTION) {
            return $this->article->getFrontendPdfsBasePath();
        } elseif ($this->type == self::FILE_IMAGE_OPTION) {
            return $this->article->getFrontendImagesBasePath();
        }
    }
    
    public function getBackendPath() {
        
        if ($this->type == self::FILE_PDF_OPTION) {
            return $this->article->getBackendPdfsBasePath();
        } elseif ($this->type == self::FILE_IMAGE_OPTION) {
            return $this->article->getBackendImagesBasePath();
        }
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['file'], 'safe'],
            [['filename', 'type'], 'string'],
            [['file'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
        ];
    }

    public function getTypeOptions() {
        return [self::FILE_PDF_OPTION => 'Pdf', self::FILE_IMAGE_OPTION => 'Image'];
    }

    protected function saveFile($file, $path) {

        if ($path) {

            if (!is_dir($path)) {

                if (!FileHelper::createDirectory($path, $this->mode, true)) {
                    return false;
                }
            }
            
            $fileName = $path . '/' . $this->filename;
            
            if (file_exists($fileName)) {
                @unlink($fileName);
            }
            
            $file->saveAs($fileName, false);
            return true;
        }
        
        return false;
    }
}
