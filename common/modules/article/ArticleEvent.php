<?php
namespace common\modules\article;

use yii\base\Event;

class ArticleEvent extends Event
{
    public $id;
    public $title;
    public $url;
    public $categoryIds;
}

