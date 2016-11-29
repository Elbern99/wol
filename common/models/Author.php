<?php

namespace common\models;

use Yii;
use common\modules\author\contracts\AuthorInterface;
use common\modules\eav\contracts\EntityModelInterface;

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
class Author extends \yii\db\ActiveRecord implements AuthorInterface, EntityModelInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'author';
    }
    
    public static function getBaseFolder() {
        return 'authors';
    }
    
    public function getAvatarBaseUrl() {
        return '/uploads/' . self::getBaseFolder() . '/images/avatar/'.$this->avatar;
    }

    public function getFrontendImagesBasePath() {
        return Yii::getAlias('@frontend') . '/web/uploads/' . self::getBaseFolder() . '/images/avatar/';
    }

    public function getBackendImagesBasePath() {
        return Yii::getAlias('@backend') . '/web/uploads/' . self::getBaseFolder() . '/images/avatar/';
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
    
    public function getId() {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
        return $this->hasMany(ArticleAuthor::className(), ['author_id' => 'id']);
    }
    
    public function addNewAuthor($args) {
        
        $obj = Yii::createObject(self::class);
        $obj->load($args, '');

        if ($obj->save()) {
            return $obj;
        }

        return false;
    }
}
