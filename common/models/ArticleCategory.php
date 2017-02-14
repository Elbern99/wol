<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $category_id
 *
 * @property Article $article
 * @property Category $category
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'category_id'], 'required'],
            [['article_id', 'category_id'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
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
            'article_id' => Yii::t('app', 'Article ID'),
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    
    public static function getCategoryByCode($codes) {
        
        $categories = [];
        
        $subQuery = Category::find()
                ->alias('t2')
                ->select('t2.id')
                ->where('(`t2`.`lft` < `t1`.`lft`) AND (`t2`.`rgt` > `t1`.`rgt`) AND (`t2`.`lvl` > 0)');
        
        $model = Category::find()
                            ->alias('t1')
                            ->where(['t1.taxonomy_code'=>$codes])
                            ->select(['t1.id', 'parent' => $subQuery])
                            ->asArray()
                            ->orderBy('t1.id')
                            ->all();
        
        foreach ($model as $category) {
            
            if ($category['parent']) {
                $categories[] = $category['parent'];
            }
            
            $categories[] = $category['id'];
        }
        
        return array_unique($categories);
    }
    
    public static function massInsert(array $bulkInsertArray) {
        
        if (count($bulkInsertArray)) {
            
            $insertCount = Yii::$app->db->createCommand()
                    ->batchInsert(
                            self::tableName(), ['article_id', 'category_id'], $bulkInsertArray
                    )
                    ->execute();

            if ($insertCount) {
                return true;
            }
        } 
        
        return false;
    }
}
