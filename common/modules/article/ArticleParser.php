<?php

namespace common\modules\article;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityInterface;
use common\modules\eav\contracts\EntityTypeInterface;

class ArticleParser implements ParserInterface {

    use traits\ArticleParseTrait;

    private $article = null;
    private $entity = null;
    private $type = null;
    private $xml = null;
    
    protected $images = [];
    protected $gaImage = '';
    protected $sources = [];
    protected $furtherReading = null;
    protected $keyReferences = null;
    protected $addReferences = null;

    public function __construct(ArticleInterface $article, EntityInterface $entity, EntityTypeInterface $type) {

        $this->article = $article;
        $this->entity = $entity;
        $this->type = $type;
    }
    
    /* need to realisation*/
    protected function getTextClass() {
        return '';
    }
    
    protected function getFurtherReading() {
        return $this->furtherReading;
    }
    
    protected function getKeyReferences() {
        return $this->keyReferences;
    }
    
    protected function getAddReferences() {
        return $this->addReferences;
    }
    
    protected function getGaImage() {
        return serialize($this->gaImage);
    }
    
    protected function getImages() {
        return serialize($this->images);
    }

    protected function addBaseTableValue() {
        
        $articleId = (int) $this->xml->teiHeader->fileDesc->publicationStmt->idno[1];
        $doi = (string) $this->xml->teiHeader->fileDesc->publicationStmt->idno[0];
        $sortKey = (string) $this->xml->teiHeader->fileDesc->titleStmt->title[1];
        $seo = (string) $this->xml->teiHeader->fileDesc->titleStmt->title[2];
        $availability = (string) $this->xml->teiHeader->fileDesc->publicationStmt->availability->p;
        $created_at = $this->xml->teiHeader->fileDesc->publicationStmt->date->attributes();
        $time = strtotime((string) $created_at['when-iso']);
        $publisher = (string) $this->xml->teiHeader->fileDesc->publicationStmt->publisher;

        $this->article->setAttribute('id', $articleId);
        $this->article->setAttribute('sort_key', $sortKey);
        $this->article->setAttribute('seo', $seo);
        $this->article->setAttribute('doi', $doi);
        $this->article->setAttribute('enabled', 1);
        $this->article->setAttribute('availability', $availability);
        $this->article->setAttribute('created_at', $time);
        $this->article->setAttribute('updated_at', $time);
        $this->article->setAttribute('publisher', $publisher);

    }

    public function parse(ReaderInterface $reader) {

        $xml = '/var/www/iza.local/backend/runtime/temporary_folder/ddd/IZAWOL.297.xml';
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));
        
        //$this->addBaseTableValue();
        //$this->getTitle();
        //$this->getAddress()
        //$this->getAddressCountry()
        //$this->getCreation()
        //$this->getKeywords()
        //$this->getTeaser()
        //$this->getFindingsPositive()
        //$this->getFindingsNegative()
        //$this->getMainMessage()
        //$this->getTermGroups()
        //$this->setImages()
        //$this->setSources()
        //$this->getRelated()
        //$this->furtherReading
        //$this->keyReferences
        //$this->addReferences
        
        //$this->getRelated();
        //$this->article->save();
        //$this->article->getAttributes()
        
        $this->setImages();
        $this->setSources();

        echo '<pre>';
        $attributes = $this->type->find()
            ->where(['name'=>'article'])
            ->with('eavTypeAttributes.eavAttribute')
            ->one();
          //.eavAttributeOptions
         foreach ($attributes->eavTypeAttributes as $attrType) {
             $related = $attrType->getRelatedRecords();
             
             foreach ($related as $attribute) {
                 $attrName = $attribute->getAttribute('name');
                 $val = $this->$attrName;
                 var_dump($attrName);
                 //var_dump($val);
             }
         }

        //var_dump($this->getFurtherReading());
        echo '</pre>';
        exit;
    }
    
    public function __get($name)
    {
        $fName = 'get'. str_replace(' ','', ucwords(str_replace('_', ' ', $name)));

        if (method_exists($this, $fName)) {
            return call_user_func(array($this, $fName));
        }
        
        throw new \Exception('Method for attribute '.$name.' not exist!');
    }

}
