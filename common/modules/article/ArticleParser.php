<?php
namespace common\modules\article;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityInterface;
use common\modules\eav\contracts\EntityTypeInterface;
use stdClass;
use yii\helpers\Html;

class ArticleParser implements ParserInterface {
    
    private $article = null;
    private $entity = null;
    private $type = null;
    private $xml = null;
    
    public function __construct(ArticleInterface $article, EntityInterface $entity, EntityTypeInterface $type) {
        
        $this->article = $article;
        $this->entity = $entity;
        $this->type = $type;
    }
    
    public function parse(ReaderInterface $reader) {
        
        $xml = '/var/www/iza.local/backend/runtime/temporary_folder/ddd/IZAWOL.297.xml';
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));

        $articleId = (int)$this->xml->teiHeader->fileDesc->publicationStmt->idno[1];
        $doi = (string)$this->xml->teiHeader->fileDesc->publicationStmt->idno[0];
        $sortKey = (string)$this->xml->teiHeader->fileDesc->titleStmt->title[1];
        $seo = (string)$this->xml->teiHeader->fileDesc->titleStmt->title[2];
        $availability = (string)$this->xml->teiHeader->fileDesc->publicationStmt->availability->p;
        $created_at = $this->xml->teiHeader->fileDesc->publicationStmt->date->attributes();
        $time = strtotime((string)$created_at['when-iso']);
        $publisher = (string)$this->xml->teiHeader->fileDesc->publicationStmt->publisher;

        $this->article->setAttribute('id', $articleId);
        $this->article->setAttribute('sort_key', $sortKey);
        $this->article->setAttribute('seo', $seo);
        $this->article->setAttribute('doi', $doi);
        $this->article->setAttribute('enabled', 1);
        $this->article->setAttribute('availability', $availability);
        $this->article->setAttribute('created_at', $time);
        $this->article->setAttribute('updated_at', $time);
        $this->article->setAttribute('publisher', $publisher);
        
        //$this->article->save();
        //$this->article->getAttributes()

        /*$attributes = $this->type->find()
                           ->where(['name'=>'article'])
                           ->with('eavTypeAttributes.eavAttribute')
                           ->one();
        //.eavAttributeOptions
        var_dump($attributes->eavTypeAttributes);*/

var_dump($this->getAddress());
        exit;
    }
    
    protected function getTitle() {
        
        $obj = new stdClass;
        $obj->title = (string)$this->xml->teiHeader->fileDesc->titleStmt->title[0];
        
        return serialize($obj);
    }
    
    protected function getAddress() {
        
        $options = [];
        $lines = $this->xml->teiHeader->fileDesc->publicationStmt->address->addrLine;
        
        foreach ($lines as $line) {
            $obj = new stdClass;
            $obj->line = (string)$line;
            $options[] = $obj;
        }

        return serialize($obj);
    }
    
    protected function getAddressCountry() {
        
        $obj = new stdClass;
        $obj->country = (string)$this->xml->teiHeader->fileDesc->publicationStmt->address->country;
        
        return serialize($obj);
    }
    
    protected function getCreation() {
        
        $obj = new stdClass;
        
        $p = xml_parser_create();
        xml_parse_into_struct($p, $this->xml->teiHeader->profileDesc->creation->asXML(), $vals);
        xml_parser_free($p);
        
        $str = $vals[0]["value"];
        $str .= Html::a($vals[1]["value"], $vals[1]["attributes"]["TARGET"]);
        $str .= $vals[2]["value"];

        $obj->main_creation = $str;
        return serialize($obj);
    }
    
    protected function getKeywords() {

        $options = [];
        $lines = $this->xml->teiHeader->profileDesc->textClass->keywords->children();

        foreach ($lines as $line) {
            $obj = new stdClass;
            $obj->word = (string)$line;
            $options[] = $obj;
        }

        return serialize($obj);
    }

}

