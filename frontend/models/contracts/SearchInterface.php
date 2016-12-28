<?php
namespace frontend\models\contracts;

interface SearchInterface {
    const ARTICLE_SEARCH_TYPE = 1;
    const BIOGRAPHY_SEARCH_TYPE = 2;

    public function getHeadingFilter();
    public function getheadingModelFilter($id = null);
}