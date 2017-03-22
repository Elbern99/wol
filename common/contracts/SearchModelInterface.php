<?php
namespace common\contracts;

interface SearchModelInterface {
    
    public static function getSearchResult($attributes);
    public static function getSearchAjaxResult($searchPhrase);
}

