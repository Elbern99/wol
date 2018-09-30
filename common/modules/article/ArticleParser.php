<?php

namespace common\modules\article;


use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\contracts\LogInterface;
use common\modules\article\contracts\ArticleInterface;
use common\modules\eav\contracts\EntityInterface;
use common\modules\eav\contracts\EntityTypeInterface;
use common\modules\eav\contracts\ValueInterface;
use common\contracts\TaxonomyInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use Yii;
use yii\base\Event;

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
class ArticleParser implements ParserInterface
{

    use traits\ArticleParseTrait;

    const EVENT_ARTICLE_CREATE = 'articleAdd';

    const EVENT_SPHINX_REINDEX = 'sphinxReindex';

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

    private $onePagerPdf = null;

    private $taxonomy = null;

    private $log;

    /*
     * property for save parsed information
     */

    protected $categoryIds = [];

    protected $images = [];

    protected $gaImage = null;

    protected $sources = [];

    protected $furtherReading = null;

    protected $keyReferences = null;

    protected $addReferences = null;

    protected $config = null;

    protected $taxonomyCodeId = [];

    protected $sourceAttribute = [];

    protected $usedImages = [];


    public function __construct(
    ArticleInterface $article, EntityInterface $entity, EntityTypeInterface $type, ValueInterface $value, TaxonomyInterface $taxonomy, LogInterface $log
    )
    {

        $this->config = Yii::$app->params['articleModelDetail'];
        $this->article = $article;
        $this->entity = $entity;
        $this->type = $type;
        $this->value = $value;
        $this->taxonomy = $taxonomy;
        $this->log = $log;

        $this->setLangData();
        $this->setTaxonomyData();
    }


    protected function getParseImagePath($name)
    {

        return $this->article->getSavePath() . '/images/' . $name;
    }


    protected function setTaxonomyData()
    {

        $data = $this->taxonomy->find()->select(['id', 'code', 'value'])->asArray()->all();
        $this->taxonomy = ArrayHelper::map($data, 'code', 'value');
        $this->taxonomyCodeId = ArrayHelper::map($data, 'id', 'code');
    }


    protected function setLangData()
    {
        $langClass = $this->config['language'];
        $this->langs = ArrayHelper::map($langClass::find()->select(['id', 'code'])->asArray()->all(), 'code', 'id');
    }


    public function getLangByCode($code)
    {

        if (isset($this->langs[$code])) {

            return $this->langs[$code];
        }

        return 0;
    }


    protected function getFullPdf()
    {

        if ($this->fullPdf) {

            $obj = new \stdClass();
            $obj->url = $this->article->getSavePath() . '/pdfs/' . $this->fullPdf;
            return serialize($obj);
        }

        return null;
    }


    protected function getOnePagerPdf()
    {

        if (is_null($this->onePagerPdf)) {
            return null;
        }

        return $this->onePagerPdf;
    }


    protected function getFurtherReading()
    {

        if (is_null($this->furtherReading)) {
            return null;
        }

        return serialize($this->furtherReading);
    }


    protected function getKeyReferences()
    {

        if (is_null($this->keyReferences)) {
            return null;
        }

        return serialize($this->keyReferences);
    }


    protected function getAddReferences()
    {

        if (is_null($this->addReferences)) {
            return null;
        }

        return serialize($this->addReferences);
    }


    protected function getGaImage()
    {

        if (is_null($this->gaImage)) {
            return null;
        }

        return $this->gaImage;
    }


    protected function getImages()
    {

        if (count($this->images)) {
            return null;
        }

        return serialize($this->images);
    }


    protected function getSources()
    {
        return serialize($this->sourceAttribute);
    }


    protected function addDataSources()
    {

        $sourceClass = $this->config['source'];
        $sourceClass::addSources($this->sourceAttribute);
    }

    
    protected function fetchVersionFromDoi($doi)
    {
        $matches = [];
        preg_match("/\.v\d+$/", $doi, $matches);
        
        if (count($matches) == 1) {
            return intval(str_replace('.v', '', $matches[0]));
        } else {
            return 1;
        }
    }

    protected function addBaseTableValue()
    {

        $articleId = $this->getIdNoByType('articleNumber');
        $doi = $this->getIdNoByType('doi');
        $version = $this->fetchVersionFromDoi($doi);
        $doi = str_replace('.v'.$version, '', $doi);
        $sortKey = $this->getTitleByType('sortKey');
        $seo = $this->getTitleByType('seo');
        $availability = (string) $this->xml->teiHeader->fileDesc->publicationStmt->availability->p;
        $created_at = $this->xml->teiHeader->fileDesc->publicationStmt->date->attributes();
        $time = strtotime((string) $created_at['when-iso']);
        $publisher = (string) $this->xml->teiHeader->fileDesc->publicationStmt->publisher;
        $title = (string) $this->xml->teiHeader->fileDesc->titleStmt->title;
        $revisionDescription = isset($this->xml->teiHeader->revisionDesc) ? (string) $this->xml->teiHeader->revisionDesc->change->p : null;

        $model = \common\models\Article::find()
            ->where(['article_number' => $articleId])->andWhere(['version' => $version])
            ->one();
            
            
            //findOne(['version' => $version, 'article_number' => $articleId]);
        
        if (!$model) {
            $model = new \common\models\Article();
        }
        
        $this->article = $model;
        //$this->article->setAttribute('id', $articleId);
        $this->article->setAttribute('title', $title);
        $this->article->setAttribute('sort_key', $sortKey);
        $this->article->setAttribute('seo', str_replace(' ', '-', strtolower(trim($seo))));
        $this->article->setAttribute('doi', $doi);
        $this->article->setAttribute('version', $version);
        $this->article->setAttribute('enabled', 0);
        $this->article->setAttribute('availability', $availability);
        $this->article->setAttribute('created_at', $time);
        $this->article->setAttribute('updated_at', time());
        $this->article->setAttribute('publisher', $publisher);
        $this->article->setAttribute('article_number', $articleId);
        $this->article->setAttribute('revision_description', $revisionDescription);
        
        if ($this->article->isNewRecord) {
            $this->article->save();
        }
    }


    public function clearParseData()
    {

        $this->usedImages = [];
        $this->furtherReading = null;
        $this->keyReferences = null;
        $this->addReferences = null;
    }


    public function parse(ReaderInterface $reader)
    {

        $xml = $reader->getXml();
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));

        if (!isset($this->xml->teiHeader->fileDesc->publicationStmt->idno)) {
            $this->log->addLine('Xml file has wrong type');
            return $this->log;
        }

        $this->addBaseTableValue();

        $this->saveArticleImages($reader->getImages());
        $this->saveArticlePdfs($reader->getPdfs());
        $reader->removeTemporaryFolder();
        unset($reader);

        $attributes = $this->type->find()
            ->where(['name' => 'article'])
            ->with('eavTypeAttributes.eavAttribute')
            ->one();

        $this->setImages();
        $this->setSources();

        foreach ($attributes->eavTypeAttributes as $attrType) {

            $related = $attrType->getRelatedRecords();

            foreach ($related as $attribute) {

                try {

                    $attrName = $attribute->getAttribute('name');
                    $this->$attrName($attribute->getAttribute('required'));
                } catch (\Exception $e) {
                    $this->log->addLine('Attribute ' . $attribute->getAttribute('name') . ' not validated - ' . $e->getMessage() . $e->getTraceAsString());
                }
            }
        }

        if ($this->log->getCount()) {
            return $this->log;
        }

        $this->clearParseData();
        // $this->article->enabled = 1; 

        if (!$this->article->save()) {

            foreach ($this->article->getErrors() as $error) {
                $this->log->addLine(current($error));
            }

            return $this->log;
        }

        //\common\helpers\ArticleHelper::setupCurrent($this->article->article_number);
        $articleId = $this->article->id;

        $this->addArticleCategory($articleId);
        $this->addArticleAuthor($articleId);
        $this->addDataSources();

        $typeId = $attributes->id;

        $entity = $this->entity->addEntity(['model_id' => $articleId, 'type_id' => $typeId, 'name' => 'article_' . $articleId], true);

        if (is_null($entity)) {
            $this->article->delete();
            $this->log->addLine(Yii::t('app/messages', 'Entity could not be created').' '.print_r($this->entity->addingErrors, true));
            return $this->log;
        }

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
                                $result = false;
                            }
                        }
                    } else {

                        $args = [
                            'entity_id' => $entity->id,
                            'attribute_id' => $attribute->getAttribute('id'),
                            'value' => $val
                        ];

                        if (!$this->value->addEavAttribute($args)) {
                            $result = false;
                        }
                    }
                }
            }
        }

        if ($result) {

            $event = new ArticleEvent;
            $event->id = $this->article->id;
            $event->title = $this->article->title;
            $event->url = 'articles/' . $this->article->seo;
            $event->categoryIds = $this->categoryIds;
            $event->availability = $this->article->availability;
            $event->pdf = $this->article->getSavePath() . '/pdfs/' . $this->fullPdf;

            /*
             * MOVED TO: backend/controllers/IzaController::changeEnabledAjax
             * 
            if ($this->article instanceof \common\models\Article) {
                $createTest = $this->article->getCreated()->one();

                if (!$createTest) {
                    $this->article->insertOrUpdateCreateRecord();
                }
            } else {
                $createTest = null;
            }

            if (!$createTest || ($this->article->doi != $createTest->doi_control)) {
                
                if ($createTest) {
                    $createTest->doi_control = $this->article->doi;
                    $createTest->save(false, ['doi_control']);
                }
                
                Event::trigger(self::class, self::EVENT_ARTICLE_CREATE, $event);
            }
             * 
             */

            Event::trigger(self::class, self::EVENT_SPHINX_REINDEX);
        }

        return $result;
    }


    protected function addArticleAuthor($articleId)
    {

        $keys = [];
        $bulkInsertArray = [];
        $class = $this->config['article_author'];
        $codes = $this->xml->teiHeader->fileDesc->titleStmt->respStmt->persName;
        foreach ($codes as $code) {

            $p = xml_parser_create();
            xml_parse_into_struct($p, $code->asXML(), $vals);
            xml_parser_free($p);

            $keys[] = (string) $vals[0]['attributes']['XML:ID'];
            unset($vals);
        }

        $authors = $class::getAuthorByCode($keys);
        $authorIds = [];
        
        foreach ($authors as $author) {
            $bulkInsertArray[] = [
                'article_id' => $articleId,
                'author_id' => $author['id'],
            ];
        }

        $class::deleteAll(['article_id' => $articleId]);
        $class::massInsert($bulkInsertArray);
    }


    protected function addArticleCategory($articleId)
    {

        $categories = [];
        $bulkInsertArray = [];
        $codes = $this->xml->teiHeader->profileDesc->textClass->classCode;
        $class = $this->config['article_category'];

        foreach ($codes as $code) {
            $categories[] = (string) $code['scheme'];
        }

        $categories = $class::getCategoryByCode($categories);

        foreach ($categories as $category) {

            $this->categoryIds[] = $category;

            $bulkInsertArray[] = [
                'article_id' => $articleId,
                'category_id' => $category,
            ];
        }

        $class::deleteAll(['article_id' => $articleId]);
        $class::massInsert($bulkInsertArray);
    }


    protected function saveArticlePdfs($pdfs)
    {

        $baseBackendPath = $this->article->getBackendPdfsBasePath();
        $baseFrontendPath = $this->article->getFrontendPdfsBasePath();

        if (!is_dir($baseBackendPath)) {

            if (!FileHelper::createDirectory($baseBackendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages', 'Pdf article  folder could not be created'));
            }
        }

        if (!is_dir($baseFrontendPath)) {

            if (!FileHelper::createDirectory($baseFrontendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages', 'Pdf article  folder could not be created'));
            }
        }

        $langs = array_keys($this->langs);

        foreach ($pdfs as $name => $path) {

            if (preg_match("/(full)/i", $name)) {

                $name = $this->article->seo . '.pdf';
                $this->fullPdf = $name;

                $this->copyFile($path, $baseBackendPath . $name);
                $this->copyFile($path, $baseFrontendPath . $name);
            } elseif (preg_match("/(one-pager)/i", $name)) {

                $name = $this->article->seo . '.one-pager.pdf';
                $obj = new \stdClass();
                $obj->url = $this->article->getSavePath() . '/pdfs/' . $name;

                $this->onePagerPdf[] = [
                    'value' => serialize($obj),
                    'lang_id' => 0
                ];

                $this->copyFile($path, $baseBackendPath . $name);
                $this->copyFile($path, $baseFrontendPath . $name);
            } else {

                foreach ($langs as $lang) {

                    if (preg_match("/($lang)/i", $name)) {

                        $name = $this->article->seo . '.one-pager.' . $lang . '.pdf';
                        $obj = new \stdClass();
                        $obj->url = $this->article->getSavePath() . '/pdfs/' . $name;

                        $this->onePagerPdf[] = [
                            'value' => serialize($obj),
                            'lang_id' => $lang
                        ];

                        $this->copyFile($path, $baseBackendPath . $name);
                        $this->copyFile($path, $baseFrontendPath . $name);
                    }
                }
            }
        }
    }


    protected function copyFile($from, $to)
    {
        @copy($from, $to);
    }


    protected function saveArticleImages($images)
    {

        $baseBackendPath = $this->article->getBackendImagesBasePath();
        $baseFrontendPath = $this->article->getFrontendImagesBasePath();

        if (!is_dir($baseBackendPath)) {

            if (!FileHelper::createDirectory($baseBackendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages', 'Images article  folder could not be created'));
            }
        }

        if (!is_dir($baseFrontendPath)) {

            if (!FileHelper::createDirectory($baseFrontendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages', 'Images article  folder could not be created'));
            }
        }

        foreach ($images as $name => $path) {

            $this->copyFile($path, $baseBackendPath . $name);
            $this->copyFile($path, $baseFrontendPath . $name);
        }
    }


    public function __call($name, $arg)
    {

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
