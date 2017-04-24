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
use common\contracts\LogInterface;
use yii\base\Event;

class AuthorParser implements ParserInterface {

    use \common\modules\author\traits\AuthorParseTrait;
    
    const EVENT_SPHINX_REINDEX = 'sphinxReindex';
    
    private $xml;
    private $author;
    private $entity = null;
    private $type = null;
    private $value = null;
    private $config = null;
    private $log;
    private $replace = [
        '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
        '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
        '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
        'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
        'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
        'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
        'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
        'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
        'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
        'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
        'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
        'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
        'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
        'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
        'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
        '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
        'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
        'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
        'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
        'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
        'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
        'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
        'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
        'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
        'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
        'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
        'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
        'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
        '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
        'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
        'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
        'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
        'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
        'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
        'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
        'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
        'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
        'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
        'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
        'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
        'ю' => 'yu', 'я' => 'ya'
    ];
    
    /* temporary variable */
    protected $person = null;
    
    public function __construct(AuthorInterface $author, EntityInterface $entity, 
            EntityTypeInterface $type, ValueInterface $value, LogInterface $log
    ) {
        
        $this->config = Yii::$app->params['authorModelDetail'];
        $this->author = $author;
        $this->entity = $entity;
        $this->type = $type;
        $this->value = $value;
        $this->log = $log;
    }

    protected function addBaseTableValue() {

        $args = [];
        $args['author_key'] = $this->getAuthorKey();
        $args['email'] = $this->getAuthorEmail();
        $args['phone'] = $this->getAuthorPhone();
        $args['enabled'] = 1;
        $args['url'] = $this->getAuthorUrl();
        $args['avatar'] = $args['author_key'].'.jpg';
        
        $names = unserialize($this->getName());
        $url_key = '';
        
        $args['name'] = $names->first_name.' '.$names->middle_name.' '.$names->last_name;

        if (trim($names->first_name)) {
            
            $url_key .= preg_replace('/\W+/', '', strtolower(
                str_replace(array_keys($this->replace), $this->replace, $names->first_name)
            ));
            $url_key .= '-';
        }
        
        if (trim($names->middle_name)) {
            
            $url_key .= preg_replace('/\W+/', '', strtolower(
                str_replace(array_keys($this->replace), $this->replace, $names->middle_name)   
            ));
            $url_key .= '-';
        }
        
        if (trim($names->last_name)) {
            
            $url_key .= preg_replace('/\W+/', '', strtolower(
                str_replace(array_keys($this->replace), $this->replace, $names->last_name)
            ));
        }

        $args['url_key'] = $url_key;
        $args['surname'] = $names->last_name;

        $author = $this->author->addNewAuthor($args);
        
        return $author;
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
            
            if (($valid = $this->preValidationAttribute()) instanceof \common\contracts\LogInterface) {
                return $valid;
            }

            $author = $this->addBaseTableValue();
            
            if ($author === false) {
                $this->log->addLine('Author - '.$this->getAuthorKey().' cannot be created');
                return $this->log;
            }
            
            $this->setAuthorRoles($author);
            $this->setAuthorCategory($author);
            $this->personParse($author);
        }
        
        Event::trigger(self::class, self::EVENT_SPHINX_REINDEX);
        return true;

    }
    
    protected function peopleParse($people) {

        foreach ($people as $person) {

            $this->person = $person;
            
            if (($valid = $this->preValidationAttribute()) instanceof \common\contracts\LogInterface) {
                continue;
            }
            
            $author = $this->addBaseTableValue();
            
            if ($author === false) {
                $this->log->addLine('Author - '.$this->getAuthorKey().' cannot be created');
                continue;
            }
            
            $this->setAuthorRoles($author);
            $this->setAuthorCategory($author);
            $this->personParse($author);
        }
        
        if ($this->log->getCount()) {
            return $this->log;
        }
    }
    
    protected function preValidationAttribute() {
        
        $attributes = $this->type->find()
                ->where(['name' => 'author'])
                ->with('eavTypeAttributes.eavAttribute')
                ->one();

        foreach ($attributes->eavTypeAttributes as $attrType) {

            $related = $attrType->getRelatedRecords();

            foreach ($related as $attribute) {
                
                try {
                    $attrName = $attribute->getAttribute('name');
                    $val = $this->$attrName($attribute->getAttribute('required'));
                } catch(\Exception $e) {
                    $this->log->addLine('Attribute '.$attribute->getAttribute('name'). ' not validated - '. $e->getMessage());
                }

            }
        }
        
        if ($this->log->getCount()) {
            return $this->log;
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

            @copy($path, $baseBackendPath . $name);
            @copy($path, $baseFrontendPath . $name);
        }
    }

    public function setAuthorRoles($author) {

        $class = $this->config['author_roles'];
        $roles = new Roles();
        $bulkInsertArray = [];
        
        foreach ($this->person->roles->role as $role) {
            
            $label = (string)$role->attributes();
            $id = $roles->getTypeByLabel($label);

            if (!$id) {
                throw new \Exception('Role '.$label.' does not exists');
            }
            
            $bulkInsertArray[] = [
                'author_id' => $author->id,
                'role_id' => $id
            ];
        }

        $class::massInsert($bulkInsertArray);
    }
    
    public function getSubjectEditor() {
        
        foreach ($this->person->roles->role as $role) {
            
            $label = (string)$role->attributes();
            
            if ($label == 'subjectEditor') {
                return $role;
            }
        }
        
        return null;
    }
    
    public function setAuthorCategory($author) {

        $class = $this->config['article_category'];

        $bulkInsertArray = [];
        $facets = $this->person->facets;

        if ($facets) {
            
            $codes = [];
            
            foreach ($facets->facet as $area) {
                
                $label = (string)$area->attributes();
                $codes[] = $label;
            }
            
            $categories = $class::getCategoryByCode($codes);

            foreach ($categories as $category) {

                $bulkInsertArray[] = [
                    'author_id' => $author->id,
                    'category_id' => $category['id'],
                ];
            }

            $class::massInsert($bulkInsertArray);
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
            $this->log->addLine(Yii::t('app/messages','Entity could not be created'));
            return $this->log;
        }
        
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

                    $this->value->addEavAttribute($args);
                }
            }
        }
        
        return true;
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
