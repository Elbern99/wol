<?php

namespace common\models;


use Yii;


/**
 * This is the model class for table "article_deleted".
 *
 * @property integer $id
 * @property integer $article_id
 */
class ArticleDeleted extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_deleted';
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'article_id' => Yii::t('app', 'Article ID'),
        ];
    }


    /**
     * @inheritdoc
     * @return ArticleDeletedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleDeletedQuery(get_called_class());
    }
}
