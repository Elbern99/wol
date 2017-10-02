<?php

namespace common\models;


/**
 * This is the ActiveQuery class for [[AuthorLetterStatsEditor]].
 *
 * @see AuthorLetterStatsEditor
 */
class AuthorLetterStatsEditorQuery extends \yii\db\ActiveQuery
{


    /**
     * @inheritdoc
     * @return AuthorLetterStatsEditor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return AuthorLetterStatsEditor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
