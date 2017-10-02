<?php

namespace common\models;


use Yii;


/**
 * This is the model class for table "author_letter_stats".
 *
 * @property integer $id
 * @property string $author_letter
 * @property integer $author_count
 */
class AuthorLetterStats extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'author_letter_stats';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_count'], 'integer'],
            [['author_letter'], 'required'],
            [['author_letter'], 'string', 'max' => 1],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_letter' => Yii::t('app', 'Author Letter'),
            'author_count' => Yii::t('app', 'Author Count'),
        ];
    }


    /**
     * @return ActiveQuery
     */
    public function getLetter()
    {
        return $this->hasOne(AuthorLetter::className(), ['id' => 'id']);
    }


    /**
     * @inheritdoc
     * @return AuthorLetterStatsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthorLetterStatsQuery(get_called_class());
    }
}
