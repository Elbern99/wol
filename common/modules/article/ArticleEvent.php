<?php

namespace common\modules\article;


use yii\base\Event;
use common\models\Article;


class ArticleEvent extends Event
{


    public $id;

    public $title;

    public $url;

    public $categoryIds;

    public $availability;

    public $pdf;

    public $version;

    protected $_model = null;


    public function getArticleModel()
    {
        if (null === $this->_model) {
            $this->_model = Article::find()->where(['id' => $this->id])->with(['authors']);
        }

        return $this->_model;
    }
}
