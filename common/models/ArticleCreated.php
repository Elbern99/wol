<?php

namespace common\models;


use Yii;


/**
 * This is the model class for table "article_created".
 *
 * @property integer $id
 * @property integer $article_id
 * 
 * @property-read Article $article
 */
class ArticleCreated extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_created';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id'], 'required'],
            [['article_id'], 'integer'],
            [['article_id'], 'unique'],
        ];
    }


    /**
     * @return \yii\db\ActiveQuerys
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'article_id' => Yii::t('app', 'Article ID'),
        ];
    }
}
