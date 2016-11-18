<?php

namespace common\modules\eav\collection;

class Attribute {

    /**
     * @var \SplObjectStorage
     */
    private $values;
    private $name;
    private $label;

    public function __construct(string $name, string $label) {
        
        $this->values = new \SplObjectStorage();
        $this->name = $name;
        $this->label = $label;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getLabel() {
        return $this->label;
    }

    public function addValue($value) {
        $this->values->attach($value);
    }

    /*public function getValues(): \SplObjectStorage{
        return $this->values;
    } */

    /* public function __toString(): string
      {
      return $this->name;
      } */
}
