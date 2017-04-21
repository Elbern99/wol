<?php
namespace backend\models;

class Author extends \common\models\Author {
    
    use \common\helpers\FileUploadTrait;
    
    protected $files = [
        'avatar'
    ];
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_key', 'url_key', 'name'], 'required'],
            [['enabled'], 'integer'],
            [['author_key', 'phone'], 'string', 'max' => 50],
            [['surname'], 'string', 'max' => 80],
            [['email', 'url_key', 'name', 'url'], 'string', 'max' => 255],
            [['surname'], 'string', 'max' => 80],
            [['author_key'], 'unique'],
            [['avatar'], 'safe'],
            [['avatar'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
        ];
    }
    
    public function getFrontendPath() {
        return $this->getFrontendImagesBasePath();
    }
    
    public function getBackendPath() {
        return $this->getBackendImagesBasePath();
    }
}

