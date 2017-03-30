<?php
namespace backend\modules\versions;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use Yii;

class MinorVersionParser implements ParserInterface {

    use \backend\modules\versions\traits\versionTrait;
    use \common\modules\article\traits\ArticleParseTrait;
    
    protected $articleId;
    protected $xml;
    
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
            
            if ($articleParser->parse($reader)) {
                
                $this->addArticleNotice();
                $this->updatedArticle();
                return true;
            }
        }
        
        return false;
    }
}

