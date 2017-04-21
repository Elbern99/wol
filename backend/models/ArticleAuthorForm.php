<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use common\models\Author;
use common\models\ArticleAuthor;

class ArticleAuthorForm extends Model {
    
    public $author_key;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_key'], 'required'],
        ];
    }
    
    public function addAuthor($article_id) {
        
        $author = Author::find()->where(['author_key' => $this->author_key])->one();
        
        if (!$author) {
            return false;
        }
        
        $model = new ArticleAuthor();
        $model->article_id = $article_id;
        $model->author_id = $author->id;
        
        return $model->save();
    }
}