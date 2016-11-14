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
 * getAffiliation serialize stdClass
 * getPublications serialize stdClass
 * getTestimonial serialize stdClass
 * getName serialize stdClass
 * getPosition serialize stdClass
 * getDegree serialize stdClass
 * getInterests serialize stdClass
 * getExpertise serialize stdClass
 * getAuthorLanguage serialize stdClass
 * getExperienceType serialize stdClass
 * getExperienceUrl serialize stdClass
 */
trait AuthorParseTrait {

    protected function getAuthorKey() {

        $p = xml_parser_create();
        xml_parse_into_struct($p, $this->person->asXML(), $vals);
        xml_parser_free($p);
        
        if (!isset($vals[0]['attributes']['XML:ID'])) {
            throw new \Exception('Not correct xml format');
        }
        
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
    
    protected function getCountry() {
        
        $countries = [];
        
        foreach ($this->person->country as $country) {
            $code = (string) $country->attributes();
            $obj = new stdClass;
            $obj->code = $code;
            $countries[] = $obj;
        }
        
        return serialize($countries);
    }
    
    protected function getTestimonial() {
        
        $testimonials = [];
        
        foreach ($this->person->testimonial->p as $testimonial) {

            $obj = new stdClass;
            $obj->testimonial = (string) $testimonial;
            $testimonials[] = $obj;
        }

        return serialize($testimonials);
    }
    
    protected function getPublications() {
        
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
    
    protected function getAffiliation() {
        
         $obj = new stdClass;
         $obj->affiliation = (string) $this->person->affiliation;
         
         return serialize($obj);
    }
    
    protected function getPosition() {

        $positions = [
            'current' => '',
            'past' => '',
            'advisory' => ''
        ];

        $obj = new stdClass;

        foreach ($this->person->positions->position as $position) {
            
            $type = (string) $position->attributes();
            if (count($position->children()) > 1) {
                $positions[$type] = [];
                
                foreach ($position->p as $p) {
                    $positions[$type][] = (string)$p;
                }
                
            } else {
                $positions[$type] = (string) $position->p;
            }
            
        }

        $obj->current = $positions['current'];
        $obj->past = $positions['past'];
        $obj->advisory = $positions['advisory'];
        
        return serialize($obj);
    }
    
    protected function getDegree() {
        
         $obj = new stdClass;
         $obj->degree = (string) $this->person->degree;

         return serialize($obj);
    }
    
    protected function getInterests() {

        $obj = new stdClass;
        $obj->interests = (string) $this->person->interests;

        return serialize($obj);
    }
    
    
    protected function getExpertise() {

        $obj = new stdClass;
        $obj->expertise = (string) $this->person->expertise;

        return serialize($obj);
    }
    
    protected function getLanguage() {
        
        $languages = [];
        
        foreach ($this->person->languages->language as $language) {
            
            $attribute = $language->attributes();
            $obj = new stdClass;
            $obj->code = (string) $attribute->code;
            $obj->proficiency = (string) $attribute->proficiency;
            
            $languages[] = $obj;
        }
        
        return serialize($languages);
    }
    
    protected  function getExperienceType() {
        
        $experienceTypes = [];
        
        foreach ($this->person->experience->media as $media) {
            
            $obj = new stdClass;
            $obj->expertise_type = (string) $media->attributes();
            $experienceTypes[] = $obj;
        }
        
        return serialize($experienceTypes);
    }
    
    protected function getExperienceUrl() {

        $experienceUrl = [];

        foreach ($this->person->experience->url as $url) {

            $obj = new stdClass;
            $obj->url = (string)$url->attributes();
            $obj->text = (string) $url;
            $experienceUrl[] = $obj;
        }

        return serialize($experienceUrl);
    }

}
