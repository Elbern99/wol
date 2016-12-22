<?php
namespace frontend\models\category;

use common\modules\category\contracts\RepositoryInterface;
use Yii;
use common\models\ArticleCategory;
use common\models\Article;
use common\models\AuthorCategory;
use common\models\Author;
use common\modules\eav\CategoryCollection;
use yii\helpers\ArrayHelper;

class ArticleRepository implements RepositoryInterface {
    
    use \frontend\components\articles\SubjectTrait;
    
    private $current;
    
    public function __construct($currentCategory) {
        $this->current = $currentCategory;
    }
    
    private function getArticleIds($limit) {
        
        return ArticleCategory::find()
                                ->select(['article_id'])
                                ->where(['category_id' => $this->current->id])
                                ->asArray()
                                ->limit($limit)
                                ->all();
    }
    
    private function getArticlesModel($categoryIds, $order) {
        
        return Article::find()
                        ->select(['id', 'title', 'seo', 'availability', 'created_at'])
                        ->where(['enabled' => 1, 'id' => ArrayHelper::getColumn($categoryIds, 'article_id')])
                        ->with(['articleCategories' => function($query) {
                                return $query->select(['category_id', 'article_id']);
                        }])
                        ->orderBy(['created_at' => $order])
                        ->all();
    }
    
    private function getAuthorIds() {
        
        return AuthorCategory::find()->select(['author_id'])
                               ->where(['category_id' => $this->current->id])
                               ->with(['authorRoles' => function($query) {
                                   return $query->select(['role_id', 'author_id']);
                               }])
                               ->asArray()
                               ->all();
    
    }


    public function getPageParams() {
        
        $order = SORT_DESC;

        if (Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
        }

        $limit = Yii::$app->params['article_limit'];

        if (Yii::$app->request->getIsPjax()) {

            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }
        
        $subjectAreas = $this->getSubjectAreas();
        
        $categoryFormat = ArrayHelper::map($subjectAreas, 'id', function($data) {
            return ['title' => $data['title'], 'url_key' => $data['url_key']];
        });
        
        $categoryIds = $this->getArticleIds($limit);
        $authorRoleIds = $this->getAuthorIds();
        
        if (count($authorRoleIds)) {
            
            $authorIds = ArrayHelper::getColumn($authorRoleIds, 'author_id');
            $authors = Author::find()
                                ->select(['id', 'avatar'])
                                ->where(['id' => $authorIds])
                                ->asArray()
                                ->all();
            
            $authors = ArrayHelper::map($authors, 'id','avatar');
            
            $authorCollection = Yii::createObject(CategoryCollection::class);
            $authorCollection->setAttributeFilter(['name', 'affiliation']);
            $authorCollection->initCollection(Author::tableName(), $authorIds);
            $authorValues = $authorCollection->getValues();
        }
        
        $authorsRoles = [];
        $authorsValue = [];
        
        foreach ($authorRoleIds as $data) {
            
            $name = unserialize($authorValues[$data['author_id']]['name']);
            $affiliation = unserialize($authorValues[$data['author_id']]['affiliation']);
            
            $authorsValue[$data['author_id']] = [
                'avatar' => (isset($authors[$data['author_id']])) ? Author::getImageUrl($authors[$data['author_id']]) : null,
                'name' => $name->first_name.' '.$name->middle_name.' '.$name->last_name,
                'affiliation' => $affiliation->affiliation
            ];
            
            $roles = ArrayHelper::getColumn($data['authorRoles'], 'role_id');
            
            foreach ($roles as $role) {
                $authorsRoles[$role][] = $data['author_id'];
            }
        }
        
        if (!count($categoryIds)) {
            
            return [
                'category' => $this->current, 
                'subjectAreas' => $subjectAreas, 
                'collection' => [], 
                'sort' => $order,
                'limit' => $limit,
                'articleCount' => 1,
                'authorsValue' => $authorsValue,
                'authorsRoles' => $authorsRoles
            ];
        }

        $articles = $this->getArticlesModel($categoryIds, $order);
        $articlesIds = ArrayHelper::getColumn($articles, 'id');

        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $articlesIds);
        $values = $categoryCollection->getValues();
        $articlesCollection = [];

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
        
        return [
            'category' => $this->current, 
            'subjectAreas' => $subjectAreas, 
            'collection' => $articlesCollection, 
            'sort' => $order,
            'limit' => $limit,
            'articleCount' => ArticleCategory::find()->where(['category_id' => $this->current->id])->count('id'),
            'authorsValue' => $authorsValue,
            'authorsRoles' => $authorsRoles
        ];
    }
    
    public function getTamplate() {
        return 'articles';
    }
}

