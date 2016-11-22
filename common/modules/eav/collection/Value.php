<?php

namespace common\modules\eav\collection;

use common\modules\eav\collection\Attribute;

class Value {

    /**
     * @var Attribute
     */
    private $attribute;
    private $data = null; 

    public function __construct(Attribute $attribute) {
        
        $this->attribute = $attribute;
        $attribute->addValue($this);
    }
    
    public function addData(string $values) {
        $this->data = unserialize($values);
    }
    
    public function getData() {
        
        return $this->data;
    }

    public function __toString(): string {
        try {

            $str = '<h3>'.$this->attribute->getLabel().'</h3> <br>';

            if (is_array($this->data)) {

                $str .= '<p><ul>';
                foreach ($this->data as $data) {

                    foreach (get_object_vars($data) as $key => $val) {

                        $valStr = $val;

                        if (is_array($val)) {
                            
                            $valStr = '<ul><li>';
                            $valStr .= @implode('</li><li>', $val);
                            $valStr .= '</li></ul>';
                        }

                        $str .= '<li>'.$key.': '.$valStr.'</li>';
                    }
                }
                $str .= '</ul></p>';

            } elseif(is_object($this->data)) {

                foreach (get_object_vars($this->data) as $key => $val) {
                    $valStr = $val;

                    if (is_array($val)) {

                        $valStr = '<ul><li>';
                        $valStr .= @implode('</li><li>', $val);
                        $valStr .= '</li></ul>';
                    }

                    $str .= '<p>'.$key.': '.$valStr.' </p>';
                }
            }

            return $str;
            
        } catch (\Exception $ex) {
            return '';
        }
    }
    
}
