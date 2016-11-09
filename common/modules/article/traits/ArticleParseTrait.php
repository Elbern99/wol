<?php

namespace common\modules\article\traits;

use common\contracts\ReaderInterface;
use stdClass;
use yii\helpers\Html;

trait ArticleParseTrait {

    abstract public function parse(ReaderInterface $reader);
    
    protected function getRelated() {
        
        $relatedList = $this->getBackElementByType('related');
        $related = $relatedList->p->children();
        $options = [];
        
        foreach ($related as $child) {

            $id = str_replace('izawol.','',(string)$child->attributes());
            
            if (intval($id)) {
                $obj = new stdClass;
                $obj->id = (int) $id;
                $options[] = $obj;
            }
        }

        return serialize($options);
    }

    protected function getTitle() {
        
        $multiTitle = array();
        $titles = $this->xml->teiHeader->fileDesc->titleStmt->title;
        
        foreach ($titles as $title) {
            
            $p = xml_parser_create();
            xml_parse_into_struct($p, $title->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['TYPE'])) {
                continue;
            }
            
            if (isset($vals[0]['attributes']['XML:LANG'])) {
                
                $obj = new stdClass;
                $obj->title = (string) $title;
                $multiTitle[] = [
                    'value' => serialize($obj),
                    'lang_id' => (string)$vals[0]['attributes']['XML:LANG']
                ];
                
            } else {
                
                $obj = new stdClass;
                $obj->title = (string) $title;
                $multiTitle[] = [
                    'value' => serialize($obj),
                    'lang_id' => ''
                ];
            }
        }

        return $multiTitle;
    }

    protected function getAddress() {

        $options = [];
        $lines = $this->xml->teiHeader->fileDesc->publicationStmt->address->addrLine;

        foreach ($lines as $line) {
            $obj = new stdClass;
            $obj->line = (string) $line;
            $options[] = $obj;
        }

        return serialize($options);
    }

    protected function getAddressCountry() {

        $obj = new stdClass;
        $obj->country = (string) $this->xml->teiHeader->fileDesc->publicationStmt->address->country;

        return serialize($obj);
    }

    protected function getCreation() {

        $obj = new stdClass;
        $creation = $this->xml->teiHeader->profileDesc->creation;
        
        $p = xml_parser_create();
        xml_parse_into_struct($p, $creation->asXML(), $vals);
        xml_parser_free($p);

        $str = $vals[0]["value"];
        $str .= Html::a($vals[1]["value"], $vals[1]["attributes"]["TARGET"]);
        $str .= $vals[2]["value"];
        $count = count($creation);
        $i = 1;
        
        unset($vals);
        
        if ($count > $i) {

            while ($i < $count) {
                $str .= ' '.$creation[$i];
                $i++;
            }
        }

        $obj->main_creation = $str;
        return serialize($obj);
    }

    protected function getKeywords() {

        $multiOptions = [];
        $lines = $this->xml->teiHeader->profileDesc->textClass->keywords;
        $cnt = count($lines);
        $i = 0;
        
        while ($cnt > $i) {
            
            $keywords = $lines[$i]->children();
            $options = [];
            $lang = '';
            
            $p = xml_parser_create();
            xml_parse_into_struct($p, $lines[$i]->asXML(), $vals);
            xml_parser_free($p);
            
            if (isset($vals[0]['attributes']['XML:LANG'])) {
                
                $lang = $vals[0]['attributes']['XML:LANG'];
            }
            
            unset($vals);
            
            foreach ($keywords as $keyword) {

                $obj = new stdClass;
                $obj->word = (string)$keyword;
                $options[] = $obj;
            }
            
            $multiOptions[] = array(
                'value' => serialize($options),
                'lang_id' => $lang
            );
            
            $i++;
        }

        return $multiOptions;
    }
    
    protected function getBodyElementByType($type) {
        
        //body part 1
        $list = $this->xml->text->body->div->div;
        $elements = array();
        
        foreach ($list as $child) {
            
            $attrType = (string)$child->attributes();
            
            if ($attrType != $type) {
                continue;
            }
            
            $elements[] = $child;
        }
        
        return $elements;
    }
    
    protected function getBackElementByType($type) {
        
        //back part
        $list = $this->xml->text->back->div;
        
        foreach ($list as $child) {
            
            $attrType = (string)$child->attributes();
            
            if ($attrType == $type) {
                return $child;
            }
            
        }
        
        return null;
    }

    protected function getTeaser() {
        
        $teasers = $this->getBodyElementByType('teaser');
        $multiOptions = array();
        
        foreach ($teasers as $teaser) {
            
            $lang = '';
            $p = xml_parser_create();
            xml_parse_into_struct($p, $teaser->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['XML:LANG'])) {
                $lang = $vals[0]['attributes']['XML:LANG'];
            }
            
            unset($vals);
            
            $obj = new stdClass;
            $obj->teaser = (string) $teaser->p;
            
            $multiOptions[] = array(
                'value' => serialize($obj),
                'lang_id' => $lang
            );
        }

        return $multiOptions;
    }

    protected function getAbstract() {
        
        $abstracts = $this->getBodyElementByType('abstract');
        $multiOptions = array();

        foreach ($abstracts as $abstract) {

            $lang = '';
            $p = xml_parser_create();
            xml_parse_into_struct($p, $abstract->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['XML:LANG'])) {
                $lang = $vals[0]['attributes']['XML:LANG'];
            }
            
            unset($vals);
            
            $obj = new stdClass;
            $obj->abstract = (string) $abstract->p;

            $multiOptions[] = array(
                'value' => serialize($obj),
                'lang_id' => $lang
            );
        }

        return $multiOptions;

    }

    protected function getFindingsPositive() {

        $keyFindings = $this->getBodyElementByType('keyFindings');
        $multiOptions = array();

        foreach ($keyFindings as $pos) {

            $list = $pos->div->list->children();
            $lang = '';
            $p = xml_parser_create();
            xml_parse_into_struct($p, $pos->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['XML:LANG'])) {
                $lang = $vals[0]['attributes']['XML:LANG'];
            }
            
            unset($vals);
            
            $options = [];

            foreach ($list as $item) {

                $obj = new stdClass;
                $obj->item = (string) $item->p;
                $options[] = $obj;
            }

            $multiOptions[] = array(
                'value' => serialize($options),
                'lang_id' => $lang
            );
            
        }

        return $multiOptions;
    }

    protected function getFindingsNegative() {

        $keyFindings = $this->getBodyElementByType('keyFindings');
        $multiOptions = array();

        foreach ($keyFindings as $pos) {

            $list = $pos->div[1]->list->children();
            $lang = '';
            $p = xml_parser_create();
            xml_parse_into_struct($p, $pos->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['XML:LANG'])) {
                $lang = $vals[0]['attributes']['XML:LANG'];
            }
            
            unset($vals);
            
            $options = [];

            foreach ($list as $item) {

                $obj = new stdClass;
                $obj->item = (string) $item->p;
                $options[] = $obj;
            }

            $multiOptions[] = array(
                'value' => serialize($options),
                'lang_id' => $lang
            );
        }

        return $multiOptions;
    }

    protected function setReferences($item) {

        foreach ($item->div->biblStruct as $read) {

            $authors = [];
            $analitics = $read->analytic;
            $monogr = $read->monogr;
            
            if (isset($analitics->author)) {
                
                foreach ($analitics->author as $author) {
                    $name = (string) $author->persName->surname;
                    $name .= " " . $author->persName->forename . ".";
                    $authors[] = $name;
                }
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
            $this->furtherReading[] = serialize($obj);
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
                    $name = (string) $author->persName->surname;
                    $name .= " " . $author->persName->forename . ".";
                    $authors[] = $name;
                }
            }

            $date = (string) $monogr->imprint->date->attributes();

            $obj = new stdClass;
            $obj->method = '';

            if (isset($ref->note->ref)) {
                $obj->method = explode(' ', (string) $ref->note->ref->attributes());
            }

            $obj->ref = $id;

            if (isset($monogr->imprint->biblScope)) {

                foreach ($monogr->imprint->biblScope as $scope) {
                    $biblScope[] = (string) $scope->attributes()['n'];
                }
            } else {
                $biblScope = ['', '', ''];
            }

            list($biblScope1, $biblScope2, $biblScope3) = $biblScope;

            $obj->full_citation = Html::a((string) implode(', ', $authors) .
                            ' "' . $analitics->title . '" ' .
                            $monogr->title .
                            ' ' . $biblScope1 . ':' . $biblScope2 .
                            ' (' . $date . ') :' . $biblScope3 . ' ' . \Yii::t('app/text', 'Online at: DOI:') .
                            ' ' . $analitics->idno[1], $analitics->idno[0]);

            $obj->title = (string) implode(' and ', $authors) . " ({$date})";

            $sourceIds = explode(' ', (string) $bibl['corresp']);

            $obj->data_source = [];
            $obj->data_type = [];

            foreach ($sourceIds as $source) {

                $id = str_replace('#', '', $source);

                if (isset($this->sources[$id])) {
                    $obj->data_source[] = $this->sources[$id]['source'];
                    $obj->data_type[] = $this->sources[$id]['type'];
                }
            }

            $obj->countries = explode(' ', (string) $bibl['n']);
            $obj->country_codes = explode(' ', (string) $bibl['n']);

            $this->keyReferences[] = serialize($obj);
        }

        foreach ($item->div[2]->biblStruct as $read) {

            $authors = [];
            $analitics = $read->analytic;
            $monogr = $read->monogr;
            
            if (isset($analitics->author)) {
                
                foreach ($analitics->author as $author) {
                    $name = (string) $author->persName->surname;
                    $name .= " " . $author->persName->forename . ".";
                    $authors[] = $name;
                }
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
            $this->addReferences[] = serialize($obj);
        }

    }

    protected function setSources() {

        $sources = $this->getBackElementByType('sources');
        $i = 1;
        
        foreach ($sources as $source) {

            $p = xml_parser_create();
            xml_parse_into_struct($p, $source->p[1]->ref->asXML(), $vals);
            xml_parser_free($p);

            $id = $vals[0]['attributes']["XML:ID"];
            $types = explode(' ', $vals[0]['attributes']["TARGET"]);

            $link = '#';

            if (isset($source->p->ptr)) {
                $link = (string) $source->p->ptr->attributes();
            }

            $this->sources[$id] = [
                'source' => Html::a("[{$i}] " . (string) $source->head, $link),
                'type' => $types
            ];

            $i++;
        }
    }

    protected function setImages() {
        
        $figures = $this->getBackElementByType('figures');
        $iamges = $figures->children();

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

        $messages = $this->getBodyElementByType('mainMessage');
        $multiOptions = array();

        foreach ($messages as $message) {

            $lang = '';
            $p = xml_parser_create();
            xml_parse_into_struct($p, $message->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['XML:LANG'])) {
                $lang = $vals[0]['attributes']['XML:LANG'];
            }
            
            unset($vals);
            
            $obj = new stdClass;
            $obj->text = (string) $message->p;

            $multiOptions[] = array(
                'value' => serialize($obj),
                'lang_id' => $lang
            );
        }

        return $multiOptions;
    }
    
    protected function getTermGroups() {
        
        $terms = $this->getBackElementByType('termGroups');
        $options = array();
        
        if ($terms) {
            
            foreach ($terms->children() as $term) {

                $p = xml_parser_create();
                xml_parse_into_struct($p, $terms->div->asXML(), $vals);
                xml_parser_free($p);
                $obj = new stdClass;
                $obj->text = '';

                $id = $vals[0]['attributes']['XML:ID'];

                foreach ($vals as $val) {

                    if ($val['tag'] == 'P') {

                        if ($val['type'] == 'open') {
                            $obj->text .= '<p>';
                        }

                        if (isset($val['value']) && str_replace(' ', '', $val['value'])) {
                            $obj->text .= $val['value'];
                        }

                        if ($val['type'] == 'close') {
                            $obj->text .= '</p>';
                        }

                    } elseif ($val['tag'] == 'HEAD') {

                        $obj->title = $val['value'];

                    } elseif ($val['tag'] == 'EMPH') {

                        $obj->text .= Html::tag('em',$val['value']);
                    }
                }

                $options[$id] =  $obj;
            }
        }
        
        return serialize($options);
    }

    protected function getMainText() {
        
        //body part 2
        $list = $this->xml->text->body->div[1]->children();
        $options = [];

        foreach ($list as $item) {
       
            $mainText = '';
            $type = (string) $item->attributes()->type;

            if ($type == 'references') {

                $this->setReferences($item);
                break;
            }

            $obj = new stdClass;
            $obj->type = $type;
            
            if (count($item) > 1) {
                
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
                
            } else {
                
                $mainText .= $this->getChildrenData($item);
            }

            $obj->text = $mainText;
            $options[] = $obj;
        }

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
        
        $p = xml_parser_create();
        xml_parse_into_struct($p, $element->asXML(), $vals);
        xml_parser_free($p);

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
                    
                } elseif ($val['attributes']['TYPE'] == 'termGroup') {

                    $text .= Html::a($val['value'], $val['attributes']['TARGET']);
                } 
                
            } elseif ($val['tag'] == 'HEAD') {
                $text .= Html::tag('head', $val['value']);
            }
        }

        if (count($images)) {

            foreach ($images as $image) {

                if (isset($this->images[$image])) {
                    $imgObj = $this->images[$image];
                    $text .= Html::tag(
                        'p', Html::img($imgObj->path, ['alt' => $imgObj->title, 'id' => $image]), ['class' => 'article_image']
                    );
                }
            }
        }

        return $text;
    }

}
