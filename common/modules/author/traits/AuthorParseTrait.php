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
        
        if ($this->person->email) {
            return (string) $this->person->email;
        }
        
        return null;
    }

    protected function getAuthorPhone() {
        
        if ($this->person->phone) {
            
            $code = (string) $this->person->phone->attributes();
            $phone = $code . ' ' . $this->person->phone;

            return $phone;
        }
        
        return null;
    }

    protected function getAuthorUrl() {
        
        if ($this->person->url) {
            return (string)$this->person->url->attributes();
        }

        return null;
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
        
        if ($this->person->testimonial) {
            foreach ($this->person->testimonial->p as $testimonial) {

                $str  = (string) $testimonial;

                if (!$str) {
                    continue;
                }

                $obj = new stdClass;
                $obj->testimonial = $str;
                $testimonials[] = $obj;
            }
        }

        return serialize($testimonials);
    }
    
    protected function getPublications() {
        
        $publications = [];
        
        if ($this->person->publications) {
            
            foreach ($this->person->publications->p as $publication) {
                
                $str  = (string) $publication;
                
                if (!$str) {
                    continue;
                }
                
                $obj = new stdClass;
                $obj->publication = $str;
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
    
    protected function getAuthorCountry() {
        
        if ($this->person->country) {
            
            $codes = [];
            
            foreach($this->person->country as $country) {
                $obj = new stdClass;
                $obj->code = (string) $country->attributes();
                $codes[] = $obj;
            }
            
            return serialize($codes);
        }
        
        return null;
    }
    
    protected function getPosition() {
        
        if ($this->person->positions) {
            
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
        
        return null;
    }
    
    protected function getDegree() {
        
        if ($this->person->degree) {
            
            $obj = new stdClass;
            $obj->degree = (string) $this->person->degree;

            return serialize($obj);
        }
        
        return null;
    }
    
    protected function getInterests() {
       
        if ($this->person->interests) {
            
            $obj = new stdClass;
            $obj->interests = (string) $this->person->interests;

            return serialize($obj);
        }
        
        return null;
    }
    
    
    protected function getExpertise() {
        
        if ($this->person->expertise) {
            
            if (isset($this->person->expertise->p)) {

                $expertises = [];

                foreach ($this->person->expertise->p as $expertise) {
                    
                    $obj = new stdClass;
                    $obj->expertise = (string) $expertise;

                    $expertises[] = $obj;
                }
                
                return serialize($expertises);
            }
            
            $obj = new stdClass;
            $obj->expertise = (string) $this->person->expertise;

            return serialize($obj);
        }
        
        return null;
    }
    
    protected function getLanguage() {
        
        if ($this->person->languages) {
            
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
        
        return null;
    }
    
    protected  function getExperienceType() {
        
        if ($this->person->experience) {
            
            $experienceTypes = [];

            foreach ($this->person->experience->media as $media) {

                $obj = new stdClass;
                $obj->expertise_type = (string) $media->attributes();
                $experienceTypes[] = $obj;
            }

            return serialize($experienceTypes);
        }
        
        return null;
    }
    
    protected function getExperienceUrl() {
        
        if ($this->person->experience) {
            
            $experienceUrl = [];

            foreach ($this->person->experience->url as $url) {

                $obj = new stdClass;
                $obj->url = (string)$url->attributes();
                $obj->text = (string) $url;
                $experienceUrl[] = $obj;
            }

            return serialize($experienceUrl);
        }
        
        return null;
    }

}
