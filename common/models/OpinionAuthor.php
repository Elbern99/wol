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
            [['opinion_id'], 'required'],
            [['opinion_id'], 'integer'],
            [['author_name', 'author_url'], 'string'],
            [['opinion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Opinion::className(), 'targetAttribute' => ['opinion_id' => 'id']],
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

}
