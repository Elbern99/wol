<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "author_category".
 *
 * @property integer $id
 * @property integer $author_id
 * @property integer $category_id
 *
 * @property Author $author
 * @property Category $category
 */
class AuthorCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'author_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'category_id'], 'required'],
            [['author_id', 'category_id'], 'integer'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_id' => Yii::t('app', 'Author ID'),
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }
    
    public function getAuthorRoles() {
        return $this->hasMany(AuthorRoles::class, ['author_id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    
    public static function getCategoryByCode($codes) {
        return Category::find()->where(['taxonomy_code'=>$codes])->select(['id'])->asArray()->all();
    }
    
    public static function massInsert(array $bulkInsertArray) {
        
        if (count($bulkInsertArray)) {
            
            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                            self::tableName(), ['author_id', 'category_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        } 
        
        return false;
    }
}
