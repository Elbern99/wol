<?php

namespace common\helpers;


use common\models\Article;


class ArticleHelper
{


    public static function setupCurrent($articleNumber)
    {
        Article::updateAll(['is_current' => null], ['article_number' => $articleNumber]);

        $maxVersionArticle = Article::find()
            ->where(['article_number' => $articleNumber])
            ->orderBy(['version' => SORT_DESC])
            ->one();

        if ($maxVersionArticle) {
            $maxVersionArticle->is_current = 1;
            $maxVersionArticle->update(false, ['is_current']);
            Article::updateAll(['max_version' => $maxVersionArticle->version], ['article_number' => $articleNumber]);
        } else {
            throw new \Exception('Article number "' . $articleNumber . '" not found.');
        }

        return $maxVersionArticle;
    }
}
