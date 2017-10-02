<?php

namespace common\models;


/**
 * This is the ActiveQuery class for [[AuthorLetterStatsExpert]].
 *
 * @see AuthorLetterStatsExpert
 */
class AuthorLetterStatsExpertQuery extends \yii\db\ActiveQuery
{


    /**
     * @inheritdoc
     * @return AuthorLetterStatsExpert[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return AuthorLetterStatsExpert|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
