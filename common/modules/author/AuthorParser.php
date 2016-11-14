<?php

namespace common\modules\author;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\modules\author\contracts\AuthorInterface;
use common\modules\eav\contracts\EntityInterface;
use common\modules\eav\contracts\EntityTypeInterface;
use common\modules\eav\contracts\ValueInterface;
use Yii;
use yii\helpers\FileHelper;

class AuthorParser implements ParserInterface {

    use \common\modules\author\traits\AuthorParseTrait;
    
    private $xml;
    private $author;
    private $entity = null;
    private $type = null;
    private $value = null;
    
    /* temporary variable */
    protected $person = null;
    
    public function __construct(AuthorInterface $author, EntityInterface $entity, 
            EntityTypeInterface $type, ValueInterface $value
    ) {

        $this->author = $author;
        $this->entity = $entity;
        $this->type = $type;
        $this->value = $value;
        
    }

    protected function addBaseTableValue() {

        $args = [];
        $args['author_key'] = $this->getAuthorKey();
        $args['email'] = $this->getAuthorEmail();
        $args['phone'] = $this->getAuthorPhone();
        $args['enabled'] = 1;
        $args['url'] = $this->getAuthorUrl();
        $args['avatar'] = $args['author_key'].'.jpg';
        
        $author = $this->author->addNewAuthor($args);
        
        if (is_object($author) && $author->id) {
            
            return $author;
        }
        
        throw new \Exception(Yii::t('app/messages','Author could not be added'));
    }

    public function parse(ReaderInterface $reader) {

        $xml = $reader->getXml();
                
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));
        $this->saveAuthorImages($reader->getImages());
        $reader->removeTemporaryFolder();
        unset($reader);

        if (count($this->xml->contributor) > 1) {

            $this->peopleParse($this->xml->contributor);
        } else {

            $this->person = $this->xml;
            $author = $this->addBaseTableValue();
            $this->personParse($author);
        }
        
        return true;

    }
    
    protected function saveAuthorImages($images) {

        $baseBackendPath = $this->author->getBackendImagesBasePath();
        $baseFrontendPath = $this->author->getFrontendImagesBasePath();

        if (!is_dir($baseBackendPath)) {

            if (!FileHelper::createDirectory($baseBackendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages', 'Images author folder could not be created'));
            }
        }

        if (!is_dir($baseFrontendPath)) {

            if (!FileHelper::createDirectory($baseFrontendPath, 0775, true)) {
                throw new \Exception(Yii::t('app/messages', 'Images author folder could not be created'));
            }
        }

        foreach ($images as $name => $path) {

            copy($path, $baseBackendPath . $name);
            copy($path, $baseFrontendPath . $name);
        }
    }

    protected function peopleParse($people) {

        foreach ($people as $person) {

            $this->person = $person;
            $author = $this->addBaseTableValue();
            $this->personParse($author);
        }
    }

    protected function personParse($author) {
        
        $attributes = $this->type->find()
                ->where(['name' => 'author'])
                ->with('eavTypeAttributes.eavAttribute')
                ->one();

        $typeId = $attributes->id;
        $authorId = $author->id;
        $entity = $this->entity->addEntity(['model_id' => $authorId, 'type_id' => $typeId, 'name' => 'author_' . $authorId]);

        if (is_null($entity)) {

            $author->delete();
            throw new \Exception(Yii::t('app/messages', 'Entity could not be created'));
        }

        $result = true;

        foreach ($attributes->eavTypeAttributes as $attrType) {

            $related = $attrType->getRelatedRecords();

            foreach ($related as $attribute) {

                $attrName = $attribute->getAttribute('name');
                $val = $this->$attrName($attribute->getAttribute('required'));

                if (!is_null($val)) {

                    $args = [
                        'entity_id' => $entity->id,
                        'attribute_id' => $attribute->getAttribute('id'),
                        'value' => $val
                    ];

                    if (!$this->value->addEavAttribute($args)) {
                        $result[] = 'Attribute ' . $attrName . ' did not add';
                    }
                    
                }
            }
        }
        
        /*
         * Log functionality
        if (is_array($result)) {
            
        }
         */
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
