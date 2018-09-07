<?php

namespace common\helpers;


use common\models\Article;


class ArticleHelper
{


    public static function setupCurrent($articleNumber, $enabledOnly = true)
    {
        $condition = $enabledOnly ? ['article_number' => $articleNumber, 'enabled' => 1] : ['article_number' => $articleNumber];
        
        Article::updateAll(['is_current' => null], $condition);

        $maxVersionArticle = Article::find()
            ->where($condition)
            ->orderBy(['version' => SORT_DESC])
            ->one();

        if ($maxVersionArticle) {
            $maxVersionArticle->is_current = 1;
            $maxVersionArticle->update(false, ['is_current']);
            Article::updateAll(['max_version' => $maxVersionArticle->version], $condition);
        } else {
            return false;
        }

        return $maxVersionArticle;
    }
}
