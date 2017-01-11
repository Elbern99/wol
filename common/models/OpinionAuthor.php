<?php

namespace common\models;

use Yii;


class OpinionAuthor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'opinion_authors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opinion_id', 'author_id'], 'required'],
            [['opinion_id', 'author_id'], 'integer'],
            [['opinion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Opinion::className(), 'targetAttribute' => ['opinion_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'opinion_id' => Yii::t('app', 'Opinion ID'),
            'author_id' => Yii::t('app', 'Author ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpinion()
    {
        return $this->hasOne(Opinion::className(), ['id' => 'opinion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }
    

    public static function massInsert(array $bulkInsertArray) {

        if (count($bulkInsertArray)) {

            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                        self::tableName(), ['opinion_id', 'author_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        }

        return false;
    }

}
