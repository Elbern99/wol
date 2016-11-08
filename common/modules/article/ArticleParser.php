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
    
    protected $images = [];
    protected $gaImage = '';
    protected $sources = [];
    
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
        
        $this->setImages();
        $this->setSources();
        //$this->article->save();
        //$this->article->getAttributes()

        /*$attributes = $this->type->find()
                           ->where(['name'=>'article'])
                           ->with('eavTypeAttributes.eavAttribute')
                           ->one();
        //.eavAttributeOptions
        var_dump($attributes->eavTypeAttributes);*/
        
        $this->getMainText();
        //var_dump($this->getFurtherReading());
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
    
    protected function getTeaser() {

        $obj = new stdClass;
        $obj->teaser = (string)$this->xml->text->body->div->div->p;
        
        return serialize($obj);
    }
    
    protected function getAbstract() {

        $obj = new stdClass;
        $obj->abstract = (string)$this->xml->text->body->div->div[1]->p;
        
        return serialize($obj);
    }
    
    protected function getFindingsPositive() {
        
        $list = $this->xml->text->body->div->div[2]->div->list->children();
        $options = [];

        foreach ($list as $item) {

            $obj = new stdClass;
            $obj->item = (string)$item->p;
            $options[] = $obj;
        }

        return serialize($obj);
    }
    
    protected function getFindingsNegative() {
        
        $list = $this->xml->text->body->div->div[2]->div[1]->list->children();
        $options = [];

        foreach ($list as $item) {

            $obj = new stdClass;
            $obj->item = (string)$item->p;
            $options[] = $obj;
        }

        return serialize($obj);
    }
    
    protected function setReferences($item) {
       
        $furtherReading = null;
        $keyReferences = null;
        $addReferences = null;
        
        foreach ($item->div->biblStruct as $read) {
            
            $authors = [];
            $analitics = $read->analytic;
            $monogr = $read->monogr;
            
            foreach ($analitics->author as $author) {
                $name = (string)$author->persName->surname;
                $name .= " ".$author->persName->forename.".";
                $authors[] = $name;
            }
            
            $date = (string)$monogr->imprint->date->attributes();
            if (isset($monogr->imprint->biblScope)) {

                foreach ($monogr->imprint->biblScope as $scope) {
                    $biblScope[] = (string) $scope->attributes()['n'];
                }
            } else {
                $biblScope = ['', '', ''];
            }

            list($biblScope1, $biblScope2, $biblScope3) = $biblScope;

            $obj = new stdClass;
            $obj->full_citation = Html::a((string)implode(', ', $authors).
                    ' "'.$analitics->title.'" '.
                    $monogr->title.
                    ' '.$biblScope1.':'.$biblScope2.
                    ' ('.$date.') :'.$biblScope3.' '.\Yii::t('app/text', 'Online at: DOI:').
                    ' '.$analitics->idno[1], $analitics->idno[0]);
            
            $obj->title = (string) implode(' and ', $authors). " ({$date})";
            $furtherReading[] = serialize($obj);
        }
        
        foreach ($item->div[1]->biblStruct as $ref) {

            $p = xml_parser_create();
            $bibl = $ref->attributes();
            xml_parse_into_struct($p, $ref->asXML(), $vals);
            xml_parser_free($p);
            
            $id = $vals[0]['attributes']["XML:ID"];
            unset($vals);

            $authors = [];
            $analitics = $ref->analytic;
            $monogr = $ref->monogr;
            
            if (isset($analitics->author)) {
                
                foreach ($analitics->author as $author) {
                    $name = (string)$author->persName->surname;
                    $name .= " ".$author->persName->forename.".";
                    $authors[] = $name;
                }
                
            }
                
            $date = (string)$monogr->imprint->date->attributes();
            
            $obj = new stdClass;
            $obj->method = '';

            if (isset($ref->note->ref)) {
                $obj->method = explode(' ', (string) $ref->note->ref->attributes());
            }
            
            $obj->ref = $id;
            
            if (isset($monogr->imprint->biblScope)) {
                
                foreach ($monogr->imprint->biblScope as $scope) {
                    $biblScope[] = (string)$scope->attributes()['n'];
                }
                
            } else {
                $biblScope = ['','',''];
            }
            
            list($biblScope1, $biblScope2, $biblScope3) = $biblScope;
            
            $obj->full_citation = Html::a((string)implode(', ', $authors).
                    ' "'.$analitics->title.'" '.
                    $monogr->title.
                    ' '.$biblScope1.':'.$biblScope2.
                    ' ('.$date.') :'.$biblScope3.' '.\Yii::t('app/text', 'Online at: DOI:').
                    ' '.$analitics->idno[1], $analitics->idno[0]);

            $obj->title = (string) implode(' and ', $authors). " ({$date})";

            $sourceIds = explode(' ', (string)$bibl['corresp']);
            
            $obj->data_source = [];
            $obj->data_type = [];

            foreach ($sourceIds as $source) {
                
                $id = str_replace('#', '', $source);
                
                if (isset($this->sources[$id])) {
                    $obj->data_source[] = $this->sources[$id]['source'];
                    $obj->data_type[] = $this->sources[$id]['type'];
                }

            }

            $obj->countries = explode(' ', (string)$bibl['n']);
            $obj->country_codes = explode(' ', (string)$bibl['n']);

            $keyReferences[] = serialize($obj);
        }
        
        foreach ($item->div[2]->biblStruct as $read) {

            $authors = [];
            $analitics = $read->analytic;
            $monogr = $read->monogr;

            foreach ($analitics->author as $author) {
                $name = (string) $author->persName->surname;
                $name .= " " . $author->persName->forename . ".";
                $authors[] = $name;
            }

            $date = (string) $monogr->imprint->date->attributes();
            if (isset($monogr->imprint->biblScope)) {

                foreach ($monogr->imprint->biblScope as $scope) {
                    $biblScope[] = (string) $scope->attributes()['n'];
                }
            } else {
                $biblScope = ['', '', ''];
            }

            list($biblScope1, $biblScope2, $biblScope3) = $biblScope;

            $obj = new stdClass;
            $obj->full_citation = Html::a((string) implode(', ', $authors) .
                            ' "' . $analitics->title . '" ' .
                            $monogr->title .
                            ' ' . $biblScope1 . ':' . $biblScope2 .
                            ' (' . $date . ') :' . $biblScope3 . ' ' . \Yii::t('app/text', 'Online at: DOI:') .
                            ' ' . $analitics->idno[1], $analitics->idno[0]);

            $obj->title = (string) implode(' and ', $authors) . " ({$date})";
            $addReferences[] = serialize($obj);
        }
        /*echo '<pre>';
        var_dump($keyReferences[3]);
        echo '</pre>';
        exit;*/
    }
    
    protected function setSources() {
        
        $sources = $this->xml->text->back->div[2];
        $i = 1;
        $t = 0;
        foreach ($sources as $source) {
            
            $p = xml_parser_create();
            xml_parse_into_struct($p, $source->p[1]->ref->asXML(), $vals);
            xml_parser_free($p);
            
            $id = $vals[0]['attributes']["XML:ID"];
            $types = explode(' ', $vals[0]['attributes']["TARGET"]);
            
            $link = '#';
            
            if (isset($source->p->ptr)) {
               $link = (string)$source->p->ptr->attributes();
            }

            $this->sources[$id] = [
                'source' => Html::a("[{$i}] ".(string)$source->head, $link),
                'type' => $types
            ];
            
            $i++;
        }
    }
    
    protected function setImages() {
        
        $iamges = $this->xml->text->back->div->children();
        
        foreach ($iamges as $image) {
            
            $attributes = $image->attributes();
          
            if (isset($attributes['subtype'])) {
                
                $attr = $image->graphic->attributes();
                $obj = new stdClass;

                $obj->title = (string) $image->head;
                $obj->path = (string) $attr->url;
                
                $this->gaImage = $obj;
                
                continue;
                
            } else {
                
                $p = xml_parser_create();
                xml_parse_into_struct($p, $image->asXML(), $vals);
                xml_parser_free($p);
              
                if (isset($vals[0]["attributes"]['XML:ID'])) {
                    
                    $id = $vals[0]["attributes"]['XML:ID'];
                    $attr = $image->graphic->attributes();
                    $obj = new stdClass;

                    $obj->title = (string) $image->head;
                    $obj->path = (string) $attr->url;

                    $this->images[$id] = $obj;
                }
                
                continue;
            }
        }
    }
    
    protected function getMainMessage() {

        $obj = new stdClass;
        $obj->teaser = (string) $this->xml->text->body->div->div[3]->p;

        return serialize($obj);
    }

    protected function getMainText() {
        
        $list = $this->xml->text->body->div[1]->children();
        $mainText = '';
        $options = [];

        foreach ($list as $item) {
            
            $type = (string)$item->attributes()->type;
            
            if ($type == 'references') {
                
                $this->setReferences($item);
                break;
            }
            
            $obj = new stdClass;
            $obj->type = $type;
            
            foreach ($item->children() as $child) {

                $sectionText = '<div>';

                if (count($child->children()) > 1) {
                    $sectionText .= $this->getChildren($child);
                } else {
                    $sectionText .= $this->getChildrenData($child);
                }

                $sectionText .= '</div>';
                $mainText .= $sectionText;
            }
            
            $obj->text = $mainText;
            $options[] = $obj;
            
        }
var_dump($options);exit;
        return serialize($options);
    }

    private function getChildren($elements) {

        $elementsText = '';

        foreach ($elements as $element) {
            $elementsText .= $this->getChildrenData($element);
        }

        return $elementsText;
    }
    
    private function getChildrenData($element) {

        $text = '';

        $countElement = count($element->children());
        $p = xml_parser_create();
        xml_parse_into_struct($p, $element->asXML(), $vals);
        xml_parser_free($p);

        if ($countElement) {
            $images = [];

            foreach ($vals as $val) {

                if ($val['tag'] == 'P') {

                    if ($val['type'] == 'open') {
                        $text .= '<p>';
                    }

                    if (isset($val['value']) && str_replace(' ', '', $val['value'])) {
                        $text .= $val['value'];
                    }

                    if ($val['type'] == 'close') {
                        $text .= '</p>';
                    }
                } elseif ($val['tag'] == 'REF') {

                    if (!isset($val['attributes']['TYPE'])) {
                        continue;
                    }

                    if ($val['attributes']['TYPE'] == 'figure') {

                        $images[] = str_replace('#', '', $val['attributes']['TARGET']);
                        $text .= Html::a('Figure', $val['attributes']['TARGET']);
                    } elseif ($val['attributes']['TYPE'] == 'bib') {

                        $text .= Html::a('[' . $val['attributes']['N'] . ']', $val['attributes']['TARGET']);
                    }
                }
            }

            if (count($images)) {
                
                foreach ($images as $image) {

                    if (isset($this->images[$image])) {
                        $imgObj = $this->images[$image];
                        $text .= Html::tag(
                            'p',
                            Html::img($imgObj->path, ['alt'=>$imgObj->title, 'id'=>$image]),
                            ['class' => 'article_image']
                       );
                    }
                }

            }
            
        } else {
            
            $text = HTML::tag(strtolower($vals[0]['tag']), $vals[0]['value']);
        }

        return $text;
    }

}

