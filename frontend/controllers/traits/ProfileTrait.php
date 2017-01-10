<?php
namespace frontend\controllers\traits;

use Yii;
use common\models\ArticleAuthor;
use common\models\Article;
use common\models\Author;
use yii\helpers\ArrayHelper;
use common\modules\eav\CategoryCollection;

trait ProfileTrait {
    
    use \frontend\components\articles\SubjectTrait;
    
    protected $subjectAreas = [];
    
    protected function getAuthorArticles(int $authorId): array {
        
        $articlesCollection = [];
        
        $articles = Article::find()
                        ->alias('a')
                        ->select(['a.id', 'a.title', 'a.seo', 'a.availability', 'a.created_at'])
                        ->innerJoin(ArticleAuthor::tableName().' as au', 'a.id = au.article_id')
                        ->where(['a.enabled' => 1, 'au.author_id' => $authorId])
                        ->with(['articleCategories' => function($query) {
                                return $query->select(['category_id', 'article_id']);
                        }])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->all();
        
        if (!count($articles)) {
            return $articlesCollection;
        }
                        
        $articlesIds = ArrayHelper::getColumn($articles, 'id');
        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $articlesIds);
        $values = $categoryCollection->getValues();
        
        $this->subjectAreas = $this->getSubjectAreas();
        
        $categoryFormat = ArrayHelper::map($this->subjectAreas, 'id', function($data) {
            return ['title' => $data['title'], 'url_key' => $data['url_key']];
        });
        
        foreach ($articles as $article) {

            $articleCategory = [];

            foreach ($article->articleCategories as $c) {

                if (isset($categoryFormat[$c->category_id])) {

                    $articleCategory[] = '<a href="' . $categoryFormat[$c->category_id]['url_key'] . '" >' . $categoryFormat[$c->category_id]['title'] . '</a>';
                }
            }

            $articlesCollection[$article->id] = [
                'title' => $article->title,
                'url' => '/articles/' . $article->seo,
                'availability' => $article->availability,
                'teaser' => unserialize($values[$article->id]['teaser']),
                'abstract' => unserialize($values[$article->id]['abstract']),
                'created_at' => $article->created_at,
                'category' => $articleCategory,
            ];
        }
        
        return $articlesCollection;
    }
    
    protected function findAuthorsByLetter(string $letter):array {
        
        return  Author::find()
                            ->select(['name', 'url_key'])
                            ->where(['enabled' => 1])
                            ->andFilterWhere(['like', 'name', $letter.'%', false])
                            ->orderBy('name')
                            ->asArray()
                            ->all();
    }
}

