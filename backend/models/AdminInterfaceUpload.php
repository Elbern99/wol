<?php
namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;
use backend\modules\parser\contracts\UploadInterface;

/*
 * class for upload and parse archive
 */
class AdminInterfaceUpload extends Model implements UploadInterface {
    
    use \common\helpers\FileUploadTrait;
    
    public $type;
    public $archive;
    
    protected $files = [
        'archive'
    ];
    
    const ARTICLE_TYPE = 1;
    const AUTHOR_TYPE = 2;
    
    public function getActionType() {
        
        return [
            self::ARTICLE_TYPE => Yii::t('app','Article'),
            self::AUTHOR_TYPE => Yii::t('app','Author')
        ];
    }
    
    public function getTypeParse() {
        return $this->type;
    }
    
    public function getArchivePath() {
        return $this->getBackendPath().'/'.$this->archive->name;
    }
    
    public function getActionClass() {
        
        switch ($this->getTypeParse()) {
            
            case self::ARTICLE_TYPE:
                return '\common\modules\article\ArticleParser';
            break;
            case self::AUTHOR_TYPE:
                return '\common\modules\author\AuthorParser';
            break;
            default:
                throw new \Exception('Class Not Found');
        }
        
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'archive'], 'required'],
            [['archive'], 'file', 'skipOnEmpty' => false, 'extensions' => 'zip'],
        ];
    }
    
    public function getFrontendPath() {
        return false;
    }
    
    public function getBackendPath() {
        
        switch ($this->getTypeParse()) {
            
            case self::ARTICLE_TYPE:
                $cat = '/articles';
            break;
            case self::AUTHOR_TYPE:
                $cat = '/authors';
            break;
            default:
                $cat = '';
        }
        
        
        return Yii::getAlias('@backend').'/web/uploads/archive'.$cat;
    }

}