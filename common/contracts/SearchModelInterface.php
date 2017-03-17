<?php
namespace common\contracts;

interface SearchModelInterface {
    
    public static function getIndexedFields();
    public static function getIndexWeight();
    public static function getSearchAjaxResult($searchPhrase);
}

