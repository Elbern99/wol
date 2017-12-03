<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ArticleDeleted]].
 *
 * @see ArticleDeleted
 */
class ArticleDeletedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ArticleDeleted[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ArticleDeleted|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
