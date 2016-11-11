<?php

namespace common\modules\author;

use common\contracts\ParserInterface;
use common\contracts\ReaderInterface;
use common\modules\author\contracts\AuthorInterface;
use Yii;

class AuthorParser implements ParserInterface {

    use \common\modules\author\traits\AuthorParseTrait;
    
    private $xml;
    private $author;
    
    /* temporary variable */
    protected $person = null;
    protected $currentAuthor = null;
    
    public function __construct(AuthorInterface $author) {

        $this->author = $author;
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
            $this->currentAuthor = $author;
        }
        
        throw new \Exception(Yii::t('app/messages','Author could not be added'));
    }

    public function parse(ReaderInterface $reader) {

        $xml = '/var/www/iza.local/backend/runtime/temporary_folder/YUMfJrmch/people.xml';
        $this->xml = new \SimpleXMLElement(file_get_contents($xml));
       

        if (count($this->xml->contributor) > 1) {

            $this->peopleParse($this->xml->contributor);
        } else {

            $this->person = $this->xml->contributor;
            $this->addBaseTableValue();
            $this->personParse();
        }

    }

    protected function peopleParse($people) {

        foreach ($people as $person) {

            $this->person = $person;
            //$this->addBaseTableValue();
            $this->personParse();
        }
    }

    protected function personParse() {
        echo '<pre>';
        $this->getAuthorAffiliation();
        echo '</pre>';
        exit;
    }

}
