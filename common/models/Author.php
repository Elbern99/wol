<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "author".
 *
 * @property integer $id
 * @property string $author_key
 * @property string $email
 * @property string $phone
 * @property integer $enabled
 * @property string $url
 * @property string $avatar
 *
 * @property ArticleAuthor[] $articleAuthors
 */
class Author extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_key'], 'required'],
            [['enabled'], 'integer'],
            [['author_key', 'phone', 'url', 'avatar'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 255],
            [['author_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_key' => Yii::t('app', 'Author Key'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'enabled' => Yii::t('app', 'Enabled'),
            'url' => Yii::t('app', 'Url'),
            'avatar' => Yii::t('app', 'Avatar'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
        return $this->hasMany(ArticleAuthor::className(), ['author_id' => 'id']);
    }
}
