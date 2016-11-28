<?php

namespace common\modules\eav\collection;

use common\modules\eav\collection\Attribute;

class Value {

    /**
     * @var Attribute
     */
    private $attribute;
    private $data = null;
    private $multi;

    public function __construct(Attribute $attribute, $multi = false) {

        $this->multi = $multi;
        $this->attribute = $attribute;
        $attribute->addValue($this);
    }

    public function addMultiData($value) {

        $this->data[$value->lang_id] = unserialize($value->value);
    }

    public function addData($value) {
        $this->data = unserialize($value->value);
    }

    protected function getMultiData($key, $lang_key) {

        if (!is_null($lang_key)) {
            
            $lang_key = isset($this->data[$lang_key]) ? $lang_key : 0;
            $data = $this->data[$lang_key];

            if ($key) {

                if (is_object($this->data[$lang_key])) {
                    return $this->data[$lang_key]->$key;
                } elseif (is_array($this->data[$lang_key])) {
                    return $this->data[$lang_key][$key];
                }
            }

            return $data;
        }


        return $this->data;
    }

    public function getData($key = null, $lang_key = null) {

        if ($this->multi) {
            return $this->getMultiData($key, $lang_key);
        } else {

            if ($key) {

                if (is_object($this->data)) {
                    return $this->data->$key;
                } elseif (is_array($this->data)) {
                    return $this->data[$key];
                }
            }
        }

        return $this->data;
    }

    public function __toString():string {
        try {

            $str = '<h3>' . $this->attribute->getLabel() . '</h3> <br>';

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

                        $str .= '<li>' . $key . ': ' . $valStr . '</li>';
                    }
                }
                $str .= '</ul></p>';
            } elseif (is_object($this->data)) {

                foreach (get_object_vars($this->data) as $key => $val) {
                    $valStr = $val;

                    if (is_array($val)) {

                        $valStr = '<ul><li>';
                        $valStr .= @implode('</li><li>', $val);
                        $valStr .= '</li></ul>';
                    }

                    $str .= '<p>' . $key . ': ' . $valStr . ' </p>';
                }
            }

            return $str;
        } catch (\Exception $ex) {
            return '';
        }
    }

}
