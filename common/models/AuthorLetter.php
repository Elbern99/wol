<?php

namespace common\models;


use Yii;


/**
 * This is the model class for table "{{%author_letter}}".
 *
 * @property integer $id
 * @property string $letter
 */
class AuthorLetter extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%author_letter}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['letter'], 'required'],
            [['letter'], 'string', 'max' => 1],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'letter' => Yii::t('app', 'Letter'),
        ];
    }


    /**
     * @return ActiveQuery
     */
    public function getStats()
    {
        return $this->hasOne(AuthorLetterStats::className(), ['id' => 'id']);
    }


    /**
     * @inheritdoc
     * @return AuthorLetterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthorLetterQuery(get_called_class());
    }
}
