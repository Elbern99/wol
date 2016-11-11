<?php

namespace common\modules\author\traits;

use stdClass;

/*
 * Parse method
 * 
 * getName serialize stdClass
 * getAuthorCountry serialize stdClass
 * getAuthorKey string
 * getAuthorEmail string
 * getAuthorPhone string
 * getAuthorUrl string
 */
trait AuthorParseTrait {

    protected function getAuthorKey() {

        $p = xml_parser_create();
        xml_parse_into_struct($p, $this->person->asXML(), $vals);
        xml_parser_free($p);

        return (string) $vals[0]['attributes']['XML:ID'];
    }

    protected function getAuthorEmail() {
        
        return (string) $this->person->email;
    }

    protected function getAuthorPhone() {
        
        $code = (string) $this->person->phone->attributes();
        $phone = $code . ' ' . $this->person->phone;

        return $phone;
    }

    protected function getAuthorUrl() {
        
        $url = (string) $this->person->url->attributes();
        return $url;
    }
    
    protected function getName() {
        
        $names = [
            'first' => '',
            'middle' => '',
            'last' => ''
        ];
        
        $obj = new stdClass;
        
        $obj->honorific = (string)$this->person->names->honorific;
        
        foreach ($this->person->names->name as $name) {
            $type = (string)$name->attributes();
            $names[$type] = (string)$name;
        }
        
        $obj->first_name = $names['first'];
        $obj->middle_name = $names['middle'];
        $obj->last_name = $names['last'];
        
        return serialize($obj);
    }
    
    protected function getAuthorCountry() {
        
        $countries = [];
        
        foreach ($this->person->country as $country) {
            $code = (string) $country->attributes();
            $obj = new stdClass;
            $obj->code = $code;
            $countries[] = $obj;
        }
        
        return serialize($countries);
    }
    
    protected function getAuthorTestimonial() {
        
        $testimonials = [];
        
        foreach ($this->person->testimonial->p as $testimonial) {

            $obj = new stdClass;
            $obj->testimonial = (string) $testimonial;
            $testimonials[] = $obj;
        }

        return serialize($testimonials);
    }
    
    protected function getAuthorPublications() {
        
        $publications = [];
        
        if ($this->person->publications->p) {
            
            foreach ($this->person->testimonial->p as $publication) {

                $obj = new stdClass;
                $obj->publication = (string) $publication;
                $publications[] = $obj;
            }
        }

        return serialize($publications);
    }
    
    protected function getAuthorAffiliation() {
        
         $obj = new stdClass;
         $obj->affiliation = (string) $this->person->affiliation;
         
         return serialize($obj);
    }

}
