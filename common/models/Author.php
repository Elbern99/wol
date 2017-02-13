<?php

namespace common\models;

use Yii;
use common\modules\author\contracts\AuthorInterface;
use common\modules\eav\contracts\EntityModelInterface;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
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
    const AUTHOR_PREFIX = 'authors';
    
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
    
    public static function getImageUrl($image) {
        return '/uploads/' . self::getBaseFolder() . '/images/avatar/'.$image;
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
            [['author_key', 'url_key', 'name'], 'required'],
            [['enabled'], 'integer'],
            [['author_key', 'phone', 'avatar'], 'string', 'max' => 50],
            [['surname'], 'string', 'max' => 80],
            [['email', 'url_key', 'name', 'url'], 'string', 'max' => 255],
            [['surname'], 'string', 'max' => 80],
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
        
        try {
            
            $obj = Yii::createObject(self::class);
            $obj->load($args, '');

            if ($obj->save()) {
                return $obj;
            }
            
        } catch (\yii\db\Exception $e) {
            return false;
        }

        return false;
    }
    
    public static function getAuthorUrl($url_key) {
        return Url::to([self::AUTHOR_PREFIX.'/'.$url_key]);
    }
    
    public function getUrl() {
        return Url::to([self::AUTHOR_PREFIX.'/'.$this->url_key]);
    }
    
    public function getAuthorRoles($type) {
        
        $ids = AuthorRoles::find()->where(['author_id' => $this->id])->all();
        $roles = new \common\modules\author\Roles();
        
        return ArrayHelper::getColumn($ids, function($data) use ($roles, $type) {
            if (array_search($data['role_id'], $roles->getAuthorGroup()) !== false) {
                if (is_null($type)) {
                    return Yii::t('app/text', $roles->getTypeByKey($data['role_id']));
                }
                return Html::a(Yii::t('app/text', $roles->getTypeByKey($data['role_id'])), Url::to('/authors/'.$this->url_key));
            } elseif(array_search($data['role_id'], $roles->getEditorGroup()) !== false) {
                if ($type == 'editor') {
                    return Yii::t('app/text', $roles->getTypeByKey($data['role_id']));
                }
                return Html::a(Yii::t('app/text', $roles->getTypeByKey($data['role_id'])), Url::to('/editors/'.$this->url_key));
            } elseif(array_search($data['role_id'], $roles->getExpertGroup()) !== false) {
                if ($type == 'expert') {
                    return Yii::t('app/text', $roles->getTypeByKey($data['role_id']));
                }
                return Html::a(Yii::t('app/text', $roles->getTypeByKey($data['role_id'])), Url::to('/spokespeople/'.$this->url_key));
            }
        });
    }
    
    public function getAuthorCategoriesArray() {
        return  AuthorCategory::find()
                    ->select(['c.url_key', 'c.title'])
                    ->alias('ac')
                    ->innerJoin(Category::tableName().' as c', 'c.id = ac.category_id')
                    ->where(['author_id' => $this->id, 'c.active' => 1])
                    ->asArray()
                    ->all();
    }
}
