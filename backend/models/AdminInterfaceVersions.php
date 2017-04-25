<?php
namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;
use backend\modules\parser\contracts\UploadInterface;

/*
 * class for upload and parse archive
 */
class AdminInterfaceVersions extends Model implements UploadInterface {
    
    use \common\helpers\FileUploadTrait;
     
    public $type;
    public $archive;
    
    protected $files = [
        'archive'
    ];
    
    const MINOR_TYPE = 1;
    const MAJOR_TYPE = 2;
    
    public function initEvent() {
        
        $class ='\common\modules\article\ArticleParser';
        
        Event::on($class, $class::EVENT_SPHINX_REINDEX,  function ($event) {
            $command = new \backend\helpers\ConsoleRunner();
            $command->run('sphinx articlesIndex');
        });
    }
    
    public function getActionType() {
        
        return [
            self::MINOR_TYPE => Yii::t('app','Minor'),
            self::MAJOR_TYPE => Yii::t('app','Major'),
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
            
            case self::MINOR_TYPE:
                return '\backend\modules\versions\MinorVersionParser';
            break;
            case self::MAJOR_TYPE:
                return '\backend\modules\versions\MajorVersionParser';
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
        
        return Yii::getAlias('@backend').'/web/uploads/archive/versions';
    }

}