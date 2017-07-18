<?php

namespace common\modules\article\traits;

use common\contracts\ReaderInterface;
use stdClass;
use yii\helpers\Html;
use common\helpers\Country;

/*
 * extension for parse author xml
 */
trait ArticleParseTrait {

    protected $doiLink = 'http://dx.doi.org/';
    abstract public function parse(ReaderInterface $reader);
    abstract protected function getParseImagePath($name);
    abstract protected function setTaxonomyData();
     
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
    
    protected function getIdNoByType($type) {
        
        $idnos = $this->xml->teiHeader->fileDesc->publicationStmt->idno;

        foreach ($idnos as $idno) {

            $p = xml_parser_create();
            xml_parse_into_struct($p, $idno->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['TYPE'])) {

                if ($vals[0]['attributes']['TYPE'] == $type) {
                    return (string) $idno;
                }
            }
        }

        return null;
    }
    
    protected function getTitleByType($type) {
        
        $titles = $this->xml->teiHeader->fileDesc->titleStmt->title;

        foreach ($titles as $title) {

            $p = xml_parser_create();
            xml_parse_into_struct($p, $title->asXML(), $vals);
            xml_parser_free($p);

            if (isset($vals[0]['attributes']['TYPE'])) {
                
                if ($vals[0]['attributes']['TYPE'] == $type) {
                    return (string)$title;
                }
            }

        }
        
        return null;
    }
    
    protected function getAffiliationArticle() {
        
        $authors = $this->xml->teiHeader->fileDesc->titleStmt->respStmt->persName;
        $affiliations = [];
        
        foreach ($authors as $author) {
            
            $p = xml_parser_create();
            xml_parse_into_struct($p, $author->asXML(), $vals);
            xml_parser_free($p);
            
            $key = (string) $vals[0]['attributes']['XML:ID'];
            $val = (string) $author->affiliation;
            
            if (mb_strlen($key) && mb_strlen(str_replace(' ', '', $val))) {
                $affiliations[$key] = $val;
            }
            
            unset($vals);
        }

        if (count($affiliations)) {
            $obj = new stdClass;
            $obj->affiliation = $affiliations;
            
            return serialize($obj);
        }
        
        return null;
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
        if (isset($vals[1]["attributes"]["TARGET"])) {
            $str .= Html::a($vals[1]["value"], $vals[1]["attributes"]["TARGET"], ['target' => '_blank']);
        } else {
            $str .= $vals[1]["value"];
        }
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
    
    protected function initFurtherReading($readStruct) {
        
        foreach ($readStruct as $ref) {
            
            $p = xml_parser_create();
            $refAttribute = $ref->attributes();
            xml_parse_into_struct($p, $ref->asXML(), $vals);
            xml_parser_free($p);

            $id = $vals[0]['attributes']["XML:ID"];
            unset($vals);

            $authors = [];
            $editors = [];
            $authorsTitle = [];
            
            $analitics = $ref->analytic;
            $monogr = $ref->monogr;

            if (isset($analitics->author)) {

                foreach ($analitics->author as $author) {
                    
                    $name = false;
 
                    if (isset($author->persName->surname)) {  
                        $name = (string) $author->persName->surname.', ';
                        $authorsTitle[] = (string) $author->persName->surname;
                        $forenameArray = [];

                        if (isset($author->persName->forename)) {
                            foreach ($author->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    } elseif (isset($author->orgName)) {
                        $name = (string) $author->orgName;
                        $authorsTitle[] = $name;
                    }
                    
                    if ($name || trim($name)) {
                        $authors[] = $name;
                    }
                }
            }
            
            if (isset($monogr->author)) {

                foreach ($monogr->author as $author) {
                    
                    $name = false;

                    if (isset($author->persName->surname)) {  
                        $name = (string) $author->persName->surname.', ';
                        $authorsTitle[] = (string) $author->persName->surname;
                        $forenameArray = [];

                        if (isset($author->persName->forename)) {
                            foreach ($author->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    } elseif (isset($author->orgName)) {
                        $name = (string) $author->orgName;
                        $authorsTitle[] = $name;
                    }

                    if ($name || trim($name)) {
                        $authors[] = $name;
                    }
                    
                }
            }
            
            $includeEditor = (count($authorsTitle)) ? false : true;
            
            if (isset($monogr->editor)) {

                foreach ($monogr->editor as $editor) {
                    
                    $name = false;
                    
                    if (isset($editor->persName)) {
                        
                        $name = (string) $editor->persName->surname.', ';
                        if ($includeEditor) {
                            $authorsTitle[] = (string) $editor->persName->surname;
                        }
                        $forenameArray = [];
                        
                        if (isset($editor->persName->forename)) {
                            foreach ($editor->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    }
                    
                    if ($name || trim($name)) {
                        $editors[] = $name;
                    }
                    
                }
            }

            $obj = new stdClass;
            $idno = $this->getIdnoReferencesAttribute($monogr, $analitics);
            
            $biblScope_pp = '';
            $biblScope_issue = '';
            $biblScope_vol = '';
            $biblScope_volume = '';
            $doi = ($idno['doi']) ? 'DOI: '.$idno['doi'] : '';
            $referencesType = '';
            
            if ($monogr->title) {
                $monogrTitleAttribute = $monogr->title->attributes();
                $referencesType = (string)$monogrTitleAttribute->level;
            }
            
            if (isset($monogr->imprint->biblScope)) {

                $bibl = array();

                foreach ($monogr->imprint->biblScope as $scope) {

                    $attributes = $scope->attributes();
                    $type = (string) $scope['type'];
                    $bibl['biblScope_' . $type] = (string) $scope['n'];
                }

                extract($bibl);
            }
            
            $date = false;
            if ($monogr->imprint->date) {
                $date = (string) $monogr->imprint->date->attributes();
                if (!trim($date)) {
                    $date = (string) $monogr->imprint->date;
                }
            }
            
            if (count($authors)) {
                $authorsText = (string) implode(', ', $authors);
            } else {
                $authorsText = (string) implode(', ', $editors);
                $authorsText .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
            }
            
            $dateBracket = ($date) ? ' ('.$date.')': '';
            $titleQuotes = '"'.$analitics->title.'"';
            $titleItalics = '<i>'.$monogr->title.'</i>';
            $publisher = (string) $monogr->imprint->publisher;
            $pubPlace  = (string) $monogr->imprint->pubPlace;

            if ($publisher && $pubPlace) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                if (count($editors) && count($authors)) {
                    $fullCitation .= ' In: '. (string)implode(', ', $editors);
                    $fullCitation .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
                }
                
                $fullCitation .= ' '.$titleItalics.'. ';
                $fullCitation .= $pubPlace.': ';
                $fullCitation .= $publisher;

                if ($date) {
                    $fullCitation .= ', '. $date;
                }
                $fullCitation .= '.';
                
            } elseif (isset($ref->series)) {
                
                $fullCitation = $authorsText;
                
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                $fullCitation .= ' '.$titleItalics;
                $fullCitation .= ' '. (string)$ref->series->title;
                if ($ref->series->idno) {
                    $fullCitation .= ' No.'.(string)$ref->series->idno;
                }
                if ($date) {
                    $fullCitation .= ', '.$date;
                }
                $fullCitation .= '.';
                
            } elseif ($idno['doi']) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                $fullCitation .= ' '.$titleItalics;
                
                if ($biblScope_volume) {
                    $fullCitation .= ' '.$biblScope_volume;
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } elseif($biblScope_vol && $biblScope_issue) {
                    $fullCitation .= ' '.$biblScope_vol.':'.$biblScope_issue;
                    $fullCitation .= $dateBracket;
                    if ($biblScope_pp) {
                        $fullCitation .= ': '.$biblScope_pp;
                    }
                } elseif ($biblScope_vol && $biblScope_pp) {
                    $fullCitation .= ' '.$biblScope_vol.' ';
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } else {
                    $fullCitation .= ' '.$dateBracket;
                }
                
                $fullCitation .= '.';
                
            } elseif ($referencesType == 'j' && $date) {
                 
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                $fullCitation .= ' '.$titleItalics;
                
                if ($biblScope_volume) {
                    $fullCitation .= ' '.$biblScope_volume;
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } elseif($biblScope_vol && $biblScope_issue) {
                    $fullCitation .= ' '.$biblScope_vol.':'.$biblScope_issue;
                    $fullCitation .= $dateBracket;
                    if ($biblScope_pp) {
                        $fullCitation .= ': '.$biblScope_pp;
                    }
                } elseif ($biblScope_vol && $biblScope_pp) {
                    $fullCitation .= ' '.$biblScope_vol.' ';
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } else {
                    $fullCitation .= ' '.$dateBracket;
                }
                
                $fullCitation .= '.';
                
            } elseif ($referencesType == 'a' && $publisher) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                if (count($editors) && count($authors)) {
                    $fullCitation .= ' In: '. (string)implode(', ', $editors);
                    $fullCitation .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
                }
                
                $fullCitation .= ' "'.$monogr->title.'". ';
                $note = (string)$monogr->note;
                
                if ($note) {
                    $fullCitation .= $note.', ';
                }
                
                $fullCitation .= $publisher;

                if ($date) {
                    $fullCitation .= ', '. $date;
                }
                
                $fullCitation .= '.';
                
            } else {
                
                $fullCitation = $authorsText;
                
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                if ($monogr->title) {
                    $fullCitation .= ' '.$titleItalics;
                }
                if ($date) {
                    $fullCitation .= ', '.$date;
                }
                $fullCitation .= '.';
            }
     
            if ($idno['doi']) {
                $obj->full_citation = Html::a(str_replace('  ',' ',trim($fullCitation)), $this->doiLink.$idno['doi'], ['target' => '_blank']);
            } elseif ($idno['url']) {
                $obj->full_citation = Html::a(str_replace('  ',' ',trim($fullCitation)), $idno['url'], ['target' => '_blank']);
            } else {
                $obj->full_citation = str_replace('  ',' ',trim($fullCitation));
            }
            
            $title = '';
            $cnt = count($authorsTitle);
            
            if ($cnt == 1) {
                $title = current($authorsTitle);
            } elseif ($cnt == 2) {
                $title = implode(' and ', $authorsTitle);
            } elseif ($cnt > 2) {
                $title = current($authorsTitle) .' et al.';
            }
            
            $title .= ($date) ? ' ('.$date.') ': '';
            $obj->title = trim($title);
            $this->furtherReading[] = $obj;
        }
    }
    
    protected function getIdnoReferencesAttribute($monogr, $analitics) {
        
        $doi = '';
        $url = '';

        if ($monogr->idno) {

            foreach ($monogr->idno as $idno) {

                $attr = $idno->attributes();
                $type = (string)$attr->type;

                switch ($type) {
                    case 'url':
                        $url = (string)$idno;
                        break;
                    case 'doi':
                        $doi = (string)$idno;
                        break;
                }
            }
        }

        if ($analitics->idno) {

            foreach ($analitics->idno as $idno) {

                $attr = $idno->attributes();
                $type = (string)$attr->type;

                switch ($type) {
                    case 'url':
                        $url = (string)$idno;
                        break;
                    case 'doi':
                        $doi = (string)$idno;
                        break;
                }
            }
        }
        
        return ['doi' => $doi, 'url' => $url];
    }


    protected function initKeyReferences($refStruct) {
        
        foreach ($refStruct as $ref) {
            
            $p = xml_parser_create();
            $refAttribute = $ref->attributes();
            xml_parse_into_struct($p, $ref->asXML(), $vals);
            xml_parser_free($p);

            $id = $vals[0]['attributes']["XML:ID"];
            unset($vals);

            $authors = [];
            $editors = [];
            $authorsTitle = [];
            
            $analitics = $ref->analytic;
            $monogr = $ref->monogr;

            if (isset($analitics->author)) {

                foreach ($analitics->author as $author) {
                    
                    $name = false;
 
                    if (isset($author->persName->surname)) {  
                        $name = (string) $author->persName->surname.', ';
                        $authorsTitle[] = (string) $author->persName->surname;
                        $forenameArray = [];

                        if (isset($author->persName->forename)) {
                            foreach ($author->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    } elseif (isset($author->orgName)) {
                        $name = (string) $author->orgName;
                        $authorsTitle[] = $name;
                    }
                    
                    if ($name || trim($name)) {
                        $authors[] = $name;
                    }
                }
            }
            
            if (isset($monogr->author)) {

                foreach ($monogr->author as $author) {
                    
                    $name = false;

                    if (isset($author->persName->surname)) {  
                        $name = (string) $author->persName->surname.', ';
                        $authorsTitle[] = (string) $author->persName->surname;
                        $forenameArray = [];

                        if (isset($author->persName->forename)) {
                            foreach ($author->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    } elseif (isset($author->orgName)) {
                        $name = (string) $author->orgName;
                        $authorsTitle[] = $name;
                    }

                    if ($name || trim($name)) {
                        $authors[] = $name;
                    }
                    
                }
            }
            
            $includeEditor = (count($authorsTitle)) ? false : true;
            
            if (isset($monogr->editor)) {

                foreach ($monogr->editor as $editor) {
                    
                    $name = false;
                    
                    if (isset($editor->persName)) {
                        
                        $name = (string) $editor->persName->surname.', ';
                        
                        if ($includeEditor) {
                            $authorsTitle[] = (string) $editor->persName->surname;
                        }
                        
                        $forenameArray = [];
                        
                        if (isset($editor->persName->forename)) {
                            foreach ($editor->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    }
                    
                    if ($name || trim($name)) {
                        $editors[] = $name;
                    }
                    
                }
            }

            $obj = new stdClass;
            $obj->method = '';

            if (isset($ref->note->ref)) {

                $types = explode(' ', (string) $ref->note->ref->attributes());
                $methods = [];

                foreach ($types as $type) {

                    if (isset($this->taxonomy[$type])) {
                        $methods[] = $this->taxonomy[$type];
                    }
                }

                $obj->method = $methods;
            }

            $obj->ref = $id;
            $idno = $this->getIdnoReferencesAttribute($monogr, $analitics);
            
            $biblScope_pp = '';
            $biblScope_issue = '';
            $biblScope_vol = '';
            $biblScope_volume = '';
            $doi = ($idno['doi']) ? 'DOI: '.$idno['doi'] : '';
            $referencesType = '';
            
            if ($monogr->title) {
                $monogrTitleAttribute = $monogr->title->attributes();
                $referencesType = (string)$monogrTitleAttribute->level;
            }
            
            if (isset($monogr->imprint->biblScope)) {

                $bibl = array();

                foreach ($monogr->imprint->biblScope as $scope) {

                    $attributes = $scope->attributes();
                    $type = (string) $scope['type'];
                    $bibl['biblScope_' . $type] = (string) $scope['n'];
                }

                extract($bibl);
            }
            
            $date = false;
            if ($monogr->imprint->date) {
                $date = (string) $monogr->imprint->date->attributes();
                if (!trim($date)) {
                    $date = (string) $monogr->imprint->date;
                }
            }
            
            if (count($authors)) {
                $authorsText = (string) implode(', ', $authors);
            } else {
                $authorsText = (string) implode(', ', $editors);
                $authorsText .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
            }
            
            $dateBracket = ($date) ? ' ('.$date.')': '';
            $titleQuotes = '"'.$analitics->title.'"';
            $titleItalics = '<i>'.$monogr->title.'</i>';
            $publisher = (string) $monogr->imprint->publisher;
            $pubPlace  = (string) $monogr->imprint->pubPlace;

            if ($publisher && $pubPlace) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                if (count($editors) && count($authors)) {
                    $fullCitation .= ' In: '. (string)implode(', ', $editors);
                    $fullCitation .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
                }
                
                $fullCitation .= ' '.$titleItalics.'. ';
                $fullCitation .= $pubPlace.': ';
                $fullCitation .= $publisher;

                if ($date) {
                    $fullCitation .= ', '. $date;
                }
                $fullCitation .= '.';
                
            } elseif (isset($ref->series)) {
                
                $fullCitation = $authorsText;
                
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                $fullCitation .= ' '.$titleItalics;
                $fullCitation .= ' '. (string)$ref->series->title;
                if ($ref->series->idno) {
                    $fullCitation .= ' No.'.(string)$ref->series->idno;
                }
                if ($date) {
                    $fullCitation .= ', '.$date;
                }
                $fullCitation .= '.';
                
            } elseif ($idno['doi']) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                $fullCitation .= ' '.$titleItalics;
                if ($biblScope_volume) {
                    $fullCitation .= ' '.$biblScope_volume;
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } elseif($biblScope_vol && $biblScope_issue) {
                    $fullCitation .= ' '.$biblScope_vol.':'.$biblScope_issue;
                    $fullCitation .= $dateBracket;
                    if ($biblScope_pp) {
                        $fullCitation .= ': '.$biblScope_pp;
                    }
                    
                } elseif ($biblScope_vol && $biblScope_pp) {
                    $fullCitation .= ' '.$biblScope_vol.' ';
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } else {
                    $fullCitation .= ' '.$dateBracket;
                }
                
                $fullCitation .= '.';
                
            } elseif ($referencesType == 'j' && $date) {
                 
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                $fullCitation .= ' '.$titleItalics;
                
                if ($biblScope_volume) {
                    $fullCitation .= ' '.$biblScope_volume;
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } elseif($biblScope_vol && $biblScope_issue) {
                    $fullCitation .= ' '.$biblScope_vol.':'.$biblScope_issue;
                    $fullCitation .= $dateBracket;
                    if ($biblScope_pp) {
                        $fullCitation .= ': '.$biblScope_pp;
                    }
                } elseif ($biblScope_vol && $biblScope_pp) {
                    $fullCitation .= ' '.$biblScope_vol.' ';
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } else {
                    $fullCitation .= ' '.$dateBracket;
                }
                
                $fullCitation .= '.';
                
            } elseif ($referencesType == 'a' && $publisher) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                if (count($editors) && count($authors)) {
                    $fullCitation .= ' In: '. (string)implode(', ', $editors);
                    $fullCitation .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
                }
                
                $fullCitation .= ' "'.$monogr->title.'". ';
                $note = (string)$monogr->note;
                
                if ($note) {
                    $fullCitation .= $note.', ';
                }
                
                $fullCitation .= $publisher;

                if ($date) {
                    $fullCitation .= ', '. $date;
                }
                
                $fullCitation .= '.';
                
            } else {
                
                $fullCitation = $authorsText;
                
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                if ($monogr->title) {
                    $fullCitation .= ' '.$titleItalics;
                }
                if ($date) {
                    $fullCitation .= ', '.$date;
                }
                $fullCitation .= '.';
            }
     
            if ($idno['doi']) {
                $obj->full_citation = Html::a(str_replace('  ',' ',trim($fullCitation)), $this->doiLink.$idno['doi'], ['target' => '_blank']);
            } elseif ($idno['url']) {
                $obj->full_citation = Html::a(str_replace('  ',' ',trim($fullCitation)), $idno['url'], ['target' => '_blank']);
            } else {
                $obj->full_citation = str_replace('  ',' ',trim($fullCitation));
            }
            
            $title = '';
            $cnt = count($authorsTitle);
            
            if ($cnt == 1) {
                $title = current($authorsTitle);
            } elseif ($cnt == 2) {
                $title = implode(' and ', $authorsTitle);
            } elseif ($cnt > 2) {
                $title = current($authorsTitle) .' et al.';
            }
            
            $title .= ($date) ? ' ('.$date.') ': '';
            $obj->title = trim($title);

            $obj->data_source = [];
            $obj->data_type = [];

            if (isset($refAttribute['corresp'])) {

                $sourceIds = explode(' ', (string) $refAttribute['corresp']);

                foreach ($sourceIds as $source) {

                    $id = str_replace('#', '', $source);

                    if (isset($this->sources[$id])) {

                        $obj->data_source[] = $this->sources[$id]['source'];
                        $obj->data_type[] = $this->sources[$id]['type'];
                    }
                }
            }

            $obj->countries = [];
            $obj->country_codes = [];

            if (isset($refAttribute['n'])) {
                
                $codes = explode(' ', (string) $refAttribute['n']);
                $counties = [];
                
                foreach ($codes as $code) {
                    $counties[] = Country::getCountryName($code);
                }
                
                $obj->countries = $counties;
                $obj->country_codes = $codes;
            }

            $this->keyReferences[] = $obj;
        }
    }
    
    protected function initAddReferences($readStruct) {
        
        foreach ($readStruct as $read) {
            
            $authors = [];
            $editors = [];
            $authorsTitle = [];
            
            $analitics = $read->analytic;
            $readAttribute = $read->attributes();
            $monogr = $read->monogr;

            if (isset($analitics->author)) {

                foreach ($analitics->author as $author) {
                    $name = false;

                    if (isset($author->persName->surname)) {
                        $name = (string) $author->persName->surname.', ';
                        $authorsTitle[] = (string) $author->persName->surname;
                        $forenameArray = [];

                        if (isset($author->persName->forename)) {
                            foreach ($author->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    } elseif (isset($author->orgName)) {
                        $name = (string) $author->orgName;
                        $authorsTitle[] = $name;
                    }
                    
                    if ($name || trim($name)) {
                        $authors[] = $name;
                    }
                }
            }
            
            if (isset($monogr->author)) {

                foreach ($monogr->author as $author) {
                   $name = false;
                    
                    if (isset($author->persName->surname)) {
                        $name = (string) $author->persName->surname.', ';
                        $authorsTitle[] = (string) $author->persName->surname;
                        $forenameArray = [];

                        if (isset($author->persName->forename)) {
                            foreach ($author->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    } elseif (isset($author->orgName)) {
                        $name = (string) $author->orgName;
                        $authorsTitle[] = $name;
                    }
                    
                    if ($name || trim($name)) {
                        $authors[] = $name;
                    }
                }
            }
            
            $includeEditor = (count($authorsTitle)) ? false : true;
            
            if (isset($monogr->editor)) {

                foreach ($monogr->editor as $editor) {
                    
                    $name = false;
                    
                    if (isset($editor->persName)) {
                        
                        $name = (string) $editor->persName->surname.', ';
                        if ($includeEditor) {
                            $authorsTitle[] = (string) $editor->persName->surname;
                        }
                        $forenameArray = [];
                        
                        if (isset($editor->persName->forename)) {
                            foreach ($editor->persName->forename as $forename) {
                                $forenameArray[] = $forename . ".";
                            }
                        }
                        $name .= implode(' ', $forenameArray);
                    }
                    
                    if ($name || trim($name)) {
                        $editors[] = $name;
                    }
                    
                }
            }
            
            $idno = $this->getIdnoReferencesAttribute($monogr, $analitics);
            
            $date = false;
            if ($monogr->imprint->date) {
                $date = (string) $monogr->imprint->date->attributes();
                if (!trim($date)) {
                    $date = (string) $monogr->imprint->date;
                }
            }
            if (count($authors)) {
                $authorsText = (string) implode(', ', $authors);
            } else {
                $authorsText = (string) implode(', ', $editors);
                $authorsText .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
            }
            $dateBracket = ($date) ? ' ('.$date.')': '';
            $titleQuotes = '"'.$analitics->title.'"';
            $titleItalics = '<i>'.$monogr->title.'</i>';
            $publisher = (string) $monogr->imprint->publisher;
            $pubPlace  = (string) $monogr->imprint->pubPlace;
            $doi = ($idno['doi']) ? 'DOI: '.$idno['doi'] : '';
            $biblScope_pp = '';
            $biblScope_issue = '';
            $biblScope_vol = '';
            $biblScope_volume = '';
            $referencesType = '';
            
            if ($monogr->title) {
                $monogrTitleAttribute = $monogr->title->attributes();
                $referencesType = (string)$monogrTitleAttribute->level;
            }
            

            if (isset($monogr->imprint->biblScope)) {

                $bibl = array();

                foreach ($monogr->imprint->biblScope as $scope) {

                    $attributes = $scope->attributes();
                    $type = (string) $scope['type'];
                    $bibl['biblScope_' . $type] = (string) $scope['n'];
                }

                extract($bibl);
            }
            
            $obj = new stdClass;
            
            if ($publisher && $pubPlace) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                if (count($editors) && count($authors)) {
                    $fullCitation .= ' In: '. (string)implode(', ', $editors);
                    $fullCitation .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
                }
                
                $fullCitation .= ' '.$titleItalics.'. ';
                $fullCitation .= $pubPlace.': ';
                $fullCitation .= $publisher;
                if ($date) {
                    $fullCitation .= ', '. $date;
                }
                $fullCitation .= '.';
                
            } elseif (isset($read->series)) {
                
                $fullCitation = $authorsText;
                
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                $fullCitation .= ' '.$titleItalics;
                $fullCitation .= ' '. (string)$read->series->title;
                if ($read->series->idno) {
                    $fullCitation .= ' No.'.(string)$read->series->idno;
                }
                if ($date) {
                    $fullCitation .= ', '.$date;
                }
                $fullCitation .= '.';
                
            } elseif ($idno['doi']) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                $fullCitation .= ' '.$titleItalics;
                
                if ($biblScope_volume) {
                    $fullCitation .= ' '.$biblScope_volume;
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } elseif($biblScope_vol && $biblScope_issue) {
                    $fullCitation .= ' '.$biblScope_vol.':'.$biblScope_issue;
                    $fullCitation .= $dateBracket;
                    if ($biblScope_pp) {
                        $fullCitation .= ': '.$biblScope_pp;
                    }
                } elseif ($biblScope_vol && $biblScope_pp) {
                    $fullCitation .= ' '.$biblScope_vol.' ';
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } else {
                    $fullCitation .= ' '.$dateBracket;
                }
                
                $fullCitation .= '.';
                
            } elseif ($referencesType == 'j' && $date) {
                 
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                $fullCitation .= ' '.$titleItalics;
                
                if ($biblScope_volume) {
                    $fullCitation .= ' '.$biblScope_volume;
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ' :'.$biblScope_pp;
                } elseif($biblScope_vol && $biblScope_issue) {
                    $fullCitation .= ' '.$biblScope_vol.':'.$biblScope_issue;
                    $fullCitation .= $dateBracket;
                    if ($biblScope_pp) {
                        $fullCitation .= ': '.$biblScope_pp;
                    }
                } elseif ($biblScope_vol && $biblScope_pp) {
                    $fullCitation .= ' '.$biblScope_vol.' ';
                    $fullCitation .= $dateBracket;
                    $fullCitation .= ': '.$biblScope_pp;
                } else {
                    $fullCitation .= ' '.$dateBracket;
                }
                
                $fullCitation .= '.';
                
            } elseif ($referencesType == 'a' && $publisher) {
                
                $fullCitation = $authorsText;
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                if (count($editors) && count($authors)) {
                    $fullCitation .= ' In: '. (string)implode(', ', $editors);
                    $fullCitation .= (count($editors) > 1) ? ' (eds).' : ' (ed).';
                }
                
                $fullCitation .= ' "'.$monogr->title.'". ';
                $note = (string)$monogr->note;
                
                if ($note) {
                    $fullCitation .= $note.', ';
                }
                
                $fullCitation .= $publisher;

                if ($date) {
                    $fullCitation .= ', '. $date;
                }
                
                $fullCitation .= '.';
                
            } else {
                
                $fullCitation = $authorsText;
                
                if ($analitics->title) {
                    $fullCitation .= ' '.$titleQuotes;
                }
                
                if ($monogr->title) {
                    $fullCitation .= ' '.$titleItalics;
                }
                
                if ($date) {
                    $fullCitation .= ', '.$date;
                }
                $fullCitation .= '.';
            }
            
            if ($idno['doi']) {
                $obj->full_citation = Html::a(str_replace('  ',' ',trim($fullCitation)), $this->doiLink.$idno['doi'], ['target' => '_blank']);
            } elseif ($idno['url']) {
                $obj->full_citation = Html::a(str_replace('  ',' ',trim($fullCitation)), $idno['url'], ['target' => '_blank']);
            } else {
                $obj->full_citation = str_replace('  ',' ',trim($fullCitation));
            }

            $title = '';
            $cnt = count($authorsTitle);
            
            if ($cnt == 1) {
                $title = current($authorsTitle);
            } elseif ($cnt == 2) {
                $title = implode(' and ', $authorsTitle);
            } elseif ($cnt > 2) {
                $title = current($authorsTitle) .' et al.';
            }
            
            $title .= ($date) ? ' ('.$date.') ': '';
            $obj->title = trim($title);
            
            $obj->country_codes = [];

            if (isset($readAttribute['n'])) {

                $obj->country_codes = explode(' ', (string) $readAttribute['n']);
            }

            $this->addReferences[] = $obj;
        }

    }

    protected function setReferences($item) {
        
        
        foreach ($item->div as $struct) {
            
            $type = (string) $struct->attributes()['type'];

            switch ($type) {
                
                /* Further Reading */
                case 'furtherReading':
                    $this->initFurtherReading($struct->biblStruct);
                    break;
                /* Key References */
                case 'keyReferences':
                    $this->initKeyReferences($struct->biblStruct);
                    break;
                /* Add References */
                case 'addReferences':
                    $this->initAddReferences($struct->biblStruct);
                    break;
            }
            
        }

    }

    protected function setSources() {

        $sources = $this->getBackElementByType('sources');

        if (is_null($sources)) {
            return;
        }
        
        foreach ($sources as $source) {
            
            if (!isset($source->p[1]->ref)) {
                continue;
            }
            
            $p = xml_parser_create();
            xml_parse_into_struct($p, $source->p[1]->ref->asXML(), $vals);
            xml_parser_free($p);

            $id = $vals[0]['attributes']['XML:ID'];
            $types = explode(' ', $vals[0]['attributes']['TARGET']);
            $sourceText = [];
            $typeIds = [];
            
            $link = false;
            
            foreach ($types as $type) {
                
                if (isset($this->taxonomy[$type])) {
                    $sourceText[] = $this->taxonomy[$type];
                }
            }
            
            foreach ($types as $type) {
                $typeTaxId = array_search($type, $this->taxonomyCodeId);
                if (preg_match('/(IWOL_COL_40)[0-9A-Za-z\.-_]+/', $type) && $typeTaxId) {
                    $typeIds['col'] = $typeTaxId;
                } elseif (preg_match('/(IWOL_DIM_50)[0-9A-Za-z\.-_]+/', $type) && $typeTaxId) {
                    $typeIds['dim'] = $typeTaxId;
                }
            }

            if (isset($source->p->ptr)) {
                $link = (string) $source->p->ptr->attributes();
            }
            
            if ($link) {
                $title = Html::a((string) $source->head, $link, ['target' => '_blank']);
            } else {
                $title = (string) $source->head;
            }
            
            $obj = new stdClass;
            $obj->source = (string)$source->head;
            $obj->website = $link;
            $obj->types = $typeIds;
            
            $this->sourceAttribute[] = $obj;
            
            $this->sources[$id] = [
                'source' => $title,
                'type' => implode(' - ', $sourceText)
            ];

        }
    }

    protected function setImages() {
        
        $figures = $this->getBackElementByType('figures');
        $images = $figures->children();

        foreach ($images as $image) {

            $attributes = $image->attributes();
            
            $p = xml_parser_create();
            xml_parse_into_struct($p, $image->asXML(), $vals);
            xml_parser_free($p);
            
            if (isset($vals[0]['attributes']['XML:ID'])) {
                
                if (isset($attributes['subtype'])) {

                    $attr = $image->graphic->attributes();
                    $obj = new stdClass;

                    $obj->title = (string) $image->head;
                    $name = (string) $attr->url;
                    $obj->path = $this->getParseImagePath($name);
                    $obj->target = '';
                    $obj->id = $vals[0]['attributes']['XML:ID'];
                    
                    if (isset($image->head->ref)) {
                        
                        $attributes = $image->head->ref->attributes();

                        if (isset($attributes['target'])) {
                            $obj->target = (string)$attributes['target'];
                        }
                    }

                    $langId = (isset($vals[0]['attributes']['XML:LANG'])) ? $vals[0]['attributes']['XML:LANG'] : 0;

                    $this->gaImage[] = [
                        'value' => serialize($obj),
                        'lang_id' => (string)$langId
                    ];


                } else {

                    $id = $vals[0]['attributes']['XML:ID'];
                    $attr = $image->graphic->attributes();
                    $obj = new stdClass;

                    $obj->title = (string) $image->head;
                    $obj->target = '';
                    $obj->id = $id;
                    
                    if (isset($image->head->ref)) {
                        
                        $attributes = $image->head->ref->attributes();

                        if (isset($attributes['target'])) {
                            $obj->target = (string)$attributes['target'];
                        }
                    }

                    $name = (string) $attr->url;
                    $obj->path = $this->getParseImagePath($name);

                    $this->images[$id] = $obj;

                }
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

            foreach ($terms->div as $term) {
                
                $p = xml_parser_create();
                xml_parse_into_struct($p, $term->asXML(), $vals);
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
                                   
                        if ($val['type'] == 'complete') {
                            
                            $obj->text .= Html::tag('em', $val['value']);
                            
                        } else {
                            
                            if ($val['type'] == 'open') {
                                $obj->text .= '<em>';
                            }

                            if (isset($val['value']) && str_replace(' ', '', $val['value'])) {
                                $obj->text .= $val['value'];
                            }

                            if ($val['type'] == 'close') {
                                $obj->text .= '</em>';
                            }
                        }
                        
                    } elseif ($val['tag'] == 'HI') {
                        
                        if ($val['type'] == 'complete') {
                            $prop = [];
                            if(isset($val['attributes']['REND'])){
                                $prop['class'] = $val['attributes']['REND'];
                            }
                            $obj->text .= Html::tag('em', $val['value'], $prop);
                        }
                    }  elseif ($val['tag'] == 'LIST') {
                        if ($val['type'] == 'open') {
                            $obj->text .= '<ul class="content-bullete">';
                        }

                        if ($val['type'] == 'close') {
                            $obj->text .= '</ul>';
                        }
                    } elseif ($val['tag'] == 'ITEM') {
                        if ($val['type'] == 'open') {
                            $obj->text .= '<li>';
                        }

                        if ($val['type'] == 'close') {
                            $obj->text .= '</li>';
                        }

                    } elseif ($val['tag'] == 'HI') {

                        if ($val['type'] == 'complete') {
                            $prop = [];
                            if(isset($val['attributes']['REND'])){
                                $prop['class'] = $val['attributes']['REND'];
                            }
                            $obj->text .= Html::tag('em', $val['value'], $prop);
                            unset($prop);
                        }
                    } elseif ($val['tag'] == 'REF') {
                        
                        if ($val['type'] == 'complete') {
                            if (isset($val['value']) && isset($val['attributes']['TARGET'])) {
                                $obj->text .= Html::a($val['value'], $val['attributes']['TARGET']);
                            } elseif (isset($val['value'])) {
                                $obj->text .= $val['value'];
                            }
                        }
                        
                    } else {

                        if (isset($val['tag'])) {
                            if ($val['type'] == 'open') {
                                $obj->text .= Html::beginTag(strtolower($val['tag']));
                            }

                            if ($val['type'] == 'close') {
                                $obj->text .= Html::endTag(strtolower($val['tag']));
                            }

                            if ($val['type'] == 'complete') {
                                if (isset($val['value'])) {
                                    $obj->text .= Html::tag(strtolower($val['tag']), $val['value']);
                                }
                            }
                        }
                    }
                }
                
                $options[$id] =  $obj;
            }

            return serialize($options);
        }
        
        return null;
    }

    protected function getMainText() {
        
        //body part 2
        $list = $this->xml->text->body->div[1]->children();
        $options = [];

        foreach ($list as $item) {

            $type = (string) $item->attributes()->type;

            if ($type == 'references') {

                $this->setReferences($item);
                break;
            }

            $obj = new stdClass;
            $obj->type = $type;

            $obj->text = $this->getChildrenData($item);
            $options[] = $obj;
        }

        return serialize($options);
    }


    protected function getChildrenData($element) {

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
                    
                    if ($val['type'] == 'complete') {
                        $text .= Html::tag('p',$val['value']);
                    } else {
                        $text .= $val['value'];
                    }

                }

                if ($val['type'] == 'close') {
                    $text .= '</p>';
                    
                    if (count($images)) {
                        
                        $imageContent = '';
                        
                        foreach ($images as $image) {

                            if (isset($this->images[$image])) {
                                
                                $imgObj = $this->images[$image];
                                $img =  Html::img($imgObj->path, ['alt' => $imgObj->title, 'id' => $image, 'data-target' => $imgObj->target]);
                                
                                if ($imgObj->target) {
                                    $img = Html::a($img, $imgObj->target, ['class' => 'text-reference']);
                                }
                                
                                $imageContent .= Html::tag(
                                    'p', 
                                    $img, 
                                    ['class' => 'article_image']
                                );
                            }
                        }

                        if ($imageContent) {
                            $text .= Html::tag('div', $imageContent, ['class' => 'figures']);
                        }
                        
                        $images = [];
                    }
                }
                
            } elseif ($val['tag'] == 'DIV') {

                if ($val['type'] == 'open') {
                    $text .= '<div>';
                }

                if ($val['type'] == 'close') {
                    $text .= '</div>';
                }
                
            } elseif ($val['tag'] == 'REF') {

                if (!isset($val['attributes']['TYPE'])) {
                    if ($val['type'] == 'close') {
                        $text .= '</a>';
                    }
                    continue;
                }
                if ($val['attributes']['TYPE'] == 'externalLink') {
                    $text .= Html::a($val['value'], $val['attributes']['TARGET'], ['target' => '_blank']);
                }
                if ($val['attributes']['TYPE'] == 'figure') {
                    $imgId = str_replace('#', '', $val['attributes']['TARGET']);
                    
                    if (array_search($imgId, $this->usedImages) === false) {
                        $images[] = $imgId;
                        $this->usedImages[] = $imgId;
                    }
                    
                    $figNumber = false;
                    $counter = 1;
                    foreach ($this->images as $key => $attribute) {
                        if ($key == $imgId) {
                            $figNumber = $counter;
                            break;
                        }
                        $counter++;
                    }
                    $imgTitle = ($figNumber) ? 'Figure '.$figNumber : 'Illustration';

                    $text .= Html::a($imgTitle, $val['attributes']['TARGET'], ['class' => 'text-reference', 'data-type'=>'figure']);
                    
                } elseif ($val['attributes']['TYPE'] == 'bib') {

                    $text .= Html::a('[' . $val['attributes']['N'] . ']', $val['attributes']['TARGET'], ['class' => 'text-reference', 'data-type'=>'bible']);
                    
                } elseif ($val['attributes']['TYPE'] == 'termGroup') {

                    if ($val['type'] == 'open') {
                        $text .= '<a href="'.$val['attributes']['TARGET'].'" class="text-reference" data-type="term">';
                    }

                    if ($val['type'] == 'close') {
                        $text .= '</a>';
                    }

                    if ($val['type'] == 'complete') {
                        $text .= Html::a($val['value'], $val['attributes']['TARGET'], ['class' => 'text-reference', 'data-type' => 'term']);
                    }
                } 
                
            } elseif ($val['tag'] == 'HEAD') {
                if ($val['type'] == 'complete') {
                    if (isset($val['attributes']['TYPE'])) {
                        $text .= Html::tag('h4', $val['value']);
                    } else {
                        $text .= Html::tag('h3', $val['value']);
                    }
                } else {

                    if ($val['type'] == 'open') {
                        $text .= Html::beginTag('h3');
                    }

                    if (isset($val['value']) && str_replace(' ', '', $val['value'])) {
                        $text .= $val['value'];
                    }

                    if ($val['type'] == 'close') {
                        $text .= Html::endTag('h3');
                    }
                }
            } elseif ($val['tag'] == 'LIST') {
                if ($val['type'] == 'open') {
                    $text .= '<ul class="content-bullete">';
                }

                if ($val['type'] == 'close') {
                    $text .= '</ul>';
                }
            } elseif ($val['tag'] == 'ITEM') {
                if ($val['type'] == 'open') {
                    $text .= '<li>';
                }

                if ($val['type'] == 'close') {
                    $text .= '</li>';
                }
                
            } elseif ($val['tag'] == 'EMPH') {
                
                if ($val['type'] == 'complete') {
                    if (isset($val['value'])) {
                        $text .= Html::tag('em', $val['value']);
                    }   
                } else {
                    
                    if ($val['type'] == 'open') {
                        $text .= '<em>';
                    }

                    if (isset($val['value']) && str_replace(' ', '', $val['value'])) {
                        $text .= $val['value'];
                    }

                    if ($val['type'] == 'close') {
                        $text .= '</em>';
                    }
                }
                
            } elseif ($val['tag'] == 'HI') {

                if ($val['type'] == 'complete') {
                    $prop = [];
                    if(isset($val['attributes']['REND'])){
                        $prop['class'] = $val['attributes']['REND'];
                    }
                    $text .= Html::tag('em', $val['value'], $prop);
                    unset($prop);
                }
                
            } else {
                        
                if (isset($val['tag'])) {
                    if ($val['type'] == 'open') {
                        $text .= Html::beginTag(strtolower($val['tag']));
                    }

                    if ($val['type'] == 'close') {
                        $text .= Html::endTag(strtolower($val['tag']));
                    }

                    if ($val['type'] == 'complete') {
                        if (isset($val['value'])) {
                            $text .= Html::tag(strtolower($val['tag']), $val['value']);
                        }
                    }
                }
            }
        }
        
        return $text;
    }
    
    protected function getTextClass() {

        if (isset($this->xml->teiHeader->profileDesc->textClass[1])) {
            
            $textClass = $this->xml->teiHeader->profileDesc->textClass[1];
            
            $options = [];

            foreach ($textClass->classCode as $classCode) {

                $obj = new stdClass;
                $obj->code = (string)$classCode;
                $options[] = $obj;
                
            }

            return serialize($options);
        }
        
        return null;
    }

}
