<?php
namespace backend\modules\versions;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\contracts\LogInterface;
use Yii;

class MinorVersionParser implements ParserInterface {

    use \backend\modules\versions\traits\versionTrait;
    use \common\modules\article\traits\ArticleParseTrait;
    
    protected $articleId;
    protected $xml;
    protected $log;
    
    public function __construct(LogInterface $log) {
        $this->log = $log;
    }
    
    protected function getParseImagePath($name) {
        
        return '';
    }
    
    protected function setTaxonomyData() {
        
    }
    
    public function parse(ReaderInterface $reader) {

        $xml = $reader->getXml();
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));
        
        $this->articleId = $this->getIdNoByType('articleNumber');

        if ($this->removeOldVersion()) {
            
            $articleParser = Yii::createObject('\common\modules\article\ArticleParser');
            $result = $articleParser->parse($reader);
            
            if ($result instanceof \common\contracts\LogInterface) {
                return $result;
            }
            
            if ($result) {
                $this->addArticleNotice();
                $this->updatedArticle();
                return true;
            }
        }
        
        $this->log->addLine('Old Article can not be remove');
        return $this->log;
    }
}

