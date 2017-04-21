<?php
namespace backend\modules\versions;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use Exception;
use Yii;
use common\models\Article;
use common\models\VersionsArticle;

class MajorVersionParser implements ParserInterface {

    use \backend\modules\versions\traits\versionTrait;
    use \common\modules\article\traits\ArticleParseTrait;
    
    protected $articleId;
    protected $xml;
    protected $version;
    
    private $article;
    private $entity;
    
    protected function getParseImagePath($name) {
        return '';
    }
    
    protected function setTaxonomyData() {}
    
    protected function setVersionNumber() {
        
        $version = (int)$this->xml->teiHeader->revisionDesc->change->attributes();
        
        if (!$version) {
            throw new Exception('Attribute change not exists.');
        }

        $this->version = $version - 1;
    }
    
    protected function initCurrentArticle() {
        
        $this->article = Article::findOne($this->articleId);
        $this->entity = $this->getArticleEntity();
    }
    
    protected function getVersionTypeId():int {
        
        $eav = Yii::$app->modules['eav_module']->components;
        $type = $eav['type'];
        $model = $type::find()->select('id')->where(['name' => VersionsArticle::ENTITY_NAME])->one();
        
        return $model->id;
    }

    protected function createVersionArticle() {
        
        $newVersion = new VersionsArticle();
        $article = $this->article;
        
        $newVersion->load($article->getAttributes(), '');
        $newVersion->article_id = $this->articleId;
        $newVersion->version_number = $this->version;
        $newVersion->seo = $article->seo.'-'.$this->version;

        if ($newVersion->insert()) {

            $this->entity->model_id = $newVersion->getAttribute('id');
            $this->entity->type_id = $this->getVersionTypeId();
            $this->entity->name = 'version_'.$newVersion->id;
            
            if ($this->entity->update()) {
                $article->delete();
                $this->article = null;
                
                return true;
            }
        }
        
        return false;
    }
    
    protected function moveOldFiles() {
        
        $eav = Yii::$app->modules['eav_module']->components;
        $valueModel = $eav['value'];
        $attributeModel = $eav['attribute'];
        
        $attributes = $valueModel::find()
                                   ->alias('v')
                                   ->select(['v.*'])
                                   ->innerJoinWith('eavAttribute')
                                   ->where([$attributeModel::tableName().'.name' => ['ga_image', 'full_pdf', 'one_pager_pdf'], 'v.entity_id' => $this->entity->id])
                                   ->all();

        foreach ($attributes as $attribute) {
            
            switch($attribute['eavAttribute']['name']) {
            
                case 'ga_image':
                    $this->changeGaImageVersion($attribute);
                    break;
                case 'full_pdf':
                    $this->changePdfPath($attribute);
                    break;
                case 'one_pager_pdf':
                    $this->changePdfPath($attribute);
                    break;
            }
        }

    }
    
    protected function changePdfPath($value) {
        
        $data = unserialize($value['value']);
        $filePath = Yii::getAlias('@frontend').'/web'.$data->url;

        if (file_exists($filePath)) {
            $newName = preg_replace('/[\-1-9]*\.pdf/', '', $data->url);
            $newName .= '-'.$this->version.'.pdf';

            if(rename($filePath, Yii::getAlias('@frontend').'/web'.$newName)) {
                $data->url = $newName;
                $value->value = serialize($data);
                $value->save();
            }
        }
    }
    
    protected function changeGaImageVersion($value) {
        
        $data = unserialize($value['value']);
        $filePath = Yii::getAlias('@frontend').'/web'.$data->path;

        if (file_exists($filePath)) {

            $newName = preg_replace('/[\-1-9]*\.png/', '', $data->path);
            $newName .= '-'.$this->version.'.png';
            
            if(rename($filePath, Yii::getAlias('@frontend').'/web'.$newName)) {
                $data->path = $newName;
                $value->value = serialize($data);
                $value->save();
            }
        }
    }
    
    public function parse(ReaderInterface $reader) {

        $xml = $reader->getXml();
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));
        
        $this->articleId = $this->getIdNoByType('articleNumber');
        
        //version number
        $this->setVersionNumber();
        //get current article
        $this->initCurrentArticle();      
        //create version article
        if ($this->createVersionArticle()) {
            //move all files
            $this->moveOldFiles();
        }

        //add new article
        $articleParser = Yii::createObject('\common\modules\article\ArticleParser');
            
        if ($articleParser->parse($reader)) {

            $this->addArticleNotice();
            $this->updatedArticle();
            
            return true;
        }
        
        return false;
    }
}

