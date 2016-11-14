<?php

namespace common\modules\article;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityInterface;
use common\modules\eav\contracts\EntityTypeInterface;
use common\modules\eav\contracts\ValueInterface;
use common\models\Lang;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use Yii;

/* Parse Methods */

//addBaseTableValue
//getTitle
//getAddress
//getAddressCountry
//getCreation
//getKeywords
//getTeaser
//getFindingsPositive
//getFindingsNegative
//getMainMessage
//getTermGroups
//setImages
//setSources
//getRelated
//furtherReading
//keyReferences
//addReferences

/*
 * class parse article
 */
class ArticleParser implements ParserInterface {

    use traits\ArticleParseTrait;

    /*
     * property for additional object, files
     */
    private $article = null;
    private $entity = null;
    private $type = null;
    private $value = null;
    private $xml = null;
    private $langs = [];
    private $fullPdf = '';
    private $onePagerPdf = '';
    
    /*
     * property for save parsed information
     */
    protected $images = null;
    protected $gaImage = '';
    protected $sources = [];
    protected $furtherReading = null;
    protected $keyReferences = null;
    protected $addReferences = null;

    public function __construct(ArticleInterface $article, EntityInterface $entity, EntityTypeInterface $type, ValueInterface $value
    ) {

        $this->article = $article;
        $this->entity = $entity;
        $this->type = $type;
        $this->value = $value;
        
        $this->setLangData();
    }
    
    protected function getParseImagePath($name) {
        return $this->article->getFrontendImagesBasePath().$name;
    }
    
    protected function setLangData() {
        
        $this->langs = ArrayHelper::map(Lang::find()->select(['id', 'code'])->asArray()->all(), 'code', 'id');
    }
    
    public function getLangByCode($code) {
        
        if (isset($this->langs[$code])) {
            
            return $this->langs[$code];
        }
        
        return 0;
    }

    /* need to realisation */
    protected function getTextClass() {
        
        return '';
    }
    
    protected function getFullPdf() {
        
        if ($this->fullPdf) {
            
            $obj = new \stdClass();
            $obj->url = $this->article->getFrontendPdfsBasePath().$this->fullPdf;
            return serialize($obj);
        }
        
        return null;
    }
    
    protected function getOnePagerPdf() {
        
        if ($this->onePagerPdf) {
            
            $obj = new \stdClass();
            $obj->url = $this->article->getFrontendPdfsBasePath() . $this->onePagerPdf;
            return serialize($obj);
        }
        
        return null;
    }

    protected function getFurtherReading() {
        
        if (is_null($this->furtherReading)) {
            return null;
        }
        
        return serialize($this->furtherReading);
    }

    protected function getKeyReferences() {
        
        if (is_null($this->keyReferences)) {
            return null;
        }
        
        return serialize($this->keyReferences);
    }

    protected function getAddReferences() {
        
        if (is_null($this->addReferences)) {
            return null;
        }
        
        return serialize($this->addReferences);
    }

    protected function getGaImage() {
        
        if (is_null($this->gaImage)) {
            return null;
        }
        
        return serialize($this->gaImage);
    }

    protected function getImages() {
        
        if (is_null($this->images)) {
            return null;
        }
        
        return serialize($this->images);
    }

    protected function addBaseTableValue() {

        $articleId = $this->getIdNoByType('articleNumber');
        $doi = $this->getIdNoByType('doi');
        $sortKey = $this->getTitleByType('sortKey');
        $seo = $this->getTitleByType('seo');
        $availability = (string) $this->xml->teiHeader->fileDesc->publicationStmt->availability->p;
        $created_at = $this->xml->teiHeader->fileDesc->publicationStmt->date->attributes();
        $time = strtotime((string) $created_at['when-iso']);
        $publisher = (string) $this->xml->teiHeader->fileDesc->publicationStmt->publisher;

        $this->article->setAttribute('id', $articleId);
        $this->article->setAttribute('sort_key', $sortKey);
        $this->article->setAttribute('seo', $seo);
        $this->article->setAttribute('doi', $doi);
        $this->article->setAttribute('enabled', 0);
        $this->article->setAttribute('availability', $availability);
        $this->article->setAttribute('created_at', $time);
        $this->article->setAttribute('updated_at', $time);
        $this->article->setAttribute('publisher', $publisher);
    }

    public function parse(ReaderInterface $reader) {

        $xml = $reader->getXml();
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));
        
        $this->addBaseTableValue();
        $this->saveArticleImages($reader->getImages());
        $this->saveArticlePdfs($reader->getPdfs());
        $reader->removeTemporaryFolder();
        unset($reader);
        
        if (!$this->article->save()) {
            
            $errors = [];
            
            foreach ($this->article->getErrors() as $error) {
                $errors[] = current($error);
            }
            
            throw new \Exception(Yii::t('app/messages',"Article did not save").'  \n'. implode("\n", $errors));
        }

        $attributes = $this->type->find()
                ->where(['name' => 'article'])
                ->with('eavTypeAttributes.eavAttribute')
                ->one();

        $typeId = $attributes->id;
        $articleId = $this->article->id;
        $entity = $this->entity->addEntity(['model_id' => $articleId, 'type_id' => $typeId, 'name' => 'article_' . $articleId]);

        if (is_null($entity)) {
            
            $this->article->delete();
            throw new \Exception(Yii::t('app/messages','Entity could not be created'));
        }

        $this->setImages();
        $this->setSources();
        $result = true;
        
        foreach ($attributes->eavTypeAttributes as $attrType) {
            
            $related = $attrType->getRelatedRecords();

            foreach ($related as $attribute) {

                $attrName = $attribute->getAttribute('name');
                $val = $this->$attrName($attribute->getAttribute('required'));

                if (!is_null($val)) {

                    if (is_array($val)) {

                        foreach ($val as $v) {

                            if (!isset($v['lang_id']) || !isset($v['value'])) {
                                throw new \Exception('Invalid attribute ' . $attrName . ' data!');
                            }

                            $args = [
                                'entity_id' => $entity->id,
                                'attribute_id' => $attribute->getAttribute('id'),
                                'lang_id' => $this->getLangByCode($v['lang_id']),
                                'value' => $v['value']
                            ];

                            if (!$this->value->addEavAttribute($args)) {
                                $result[] = 'Attribute '. $attrName . ' did not add';
                            }
                        }
                        
                    } else {
                        
                        $args = [
                            'entity_id' => $entity->id,
                            'attribute_id' => $attribute->getAttribute('id'),
                            'value' => $val
                        ];
                        
                        if (!$this->value->addEavAttribute($args)) {
                            $result[] = 'Attribute '. $attrName . ' did not add';
                        }
                    }
                }

            }
        }
        
        return $result;
    }

    protected function saveArticlePdfs($pdfs) {

        $baseBackendPath = $this->article->getBackendPdfsBasePath();
        $baseFrontendPath = $this->article->getFrontendPdfsBasePath();

        if (!is_dir($baseBackendPath)) {

            if (!FileHelper::createDirectory($baseBackendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages','Pdf article  folder could not be created'));
            }
        }

        if (!is_dir($baseFrontendPath)) {

            if (!FileHelper::createDirectory($baseFrontendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages','Pdf article  folder could not be created'));
            }
        }

        foreach ($pdfs as $name => $path) {
            
            if (preg_match("/(full)/i", $name)) {
                $this->fullPdf = $name;
            } elseif(preg_match("/(one-pager)/i", $name)) {
                $this->onePagerPdf = $name;
            }
            
            copy($path, $baseBackendPath . $name);
            copy($path, $baseFrontendPath . $name);
        }
    }

    protected function saveArticleImages($images) {
        
        $baseBackendPath = $this->article->getBackendImagesBasePath();
        $baseFrontendPath = $this->article->getFrontendImagesBasePath();
        
        if (!is_dir($baseBackendPath)) {

            if (!FileHelper::createDirectory($baseBackendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages','Images article  folder could not be created'));
            }
        }
        
        if (!is_dir($baseFrontendPath)) {

            if (!FileHelper::createDirectory($baseFrontendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages','Images article  folder could not be created'));
            }
        }
        
        foreach ($images as $name => $path) {
            
            copy($path, $baseBackendPath.$name);
            copy($path, $baseFrontendPath.$name);
        }
    }

    public function __call($name, $arg) {
        
        $fName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));

        if (method_exists($this, $fName)) {
            return call_user_func(array($this, $fName));
        }

        if ($arg[0]) {
            throw new \Exception('Method for attribute ' . $name . ' not exist!');
        }

        return null;
    }

}
