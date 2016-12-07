<?php
namespace frontend\models\category;

use common\modules\category\contracts\RepositoryInterface;
use Yii;
use common\models\ArticleCategory;
use common\models\Article;
use common\modules\eav\CategoryCollection;
use yii\helpers\ArrayHelper;

class ArticleRepository implements RepositoryInterface {
    
    use \frontend\components\articles\SubjectTrait;
    
    private $current;
    
    public function __construct($currentCategory) {
        $this->current = $currentCategory;
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
        
        $categoryIds = ArticleCategory::find()
                                        ->select(['article_id'])
                                        ->where(['category_id' => $this->current->id])
                                        ->asArray()
                                        ->limit($limit)
                                        ->all();
        
        if (!count($categoryIds)) {
            return [
                'category' => $this->current, 
                'subjectAreas' => $subjectAreas, 
                'collection' => [], 
                'sort' => $order,
                'limit' => $limit,
                'articleCount' => 1
            ];
        }

        $articles = Article::find()
                            ->select(['id', 'title', 'seo', 'availability', 'created_at'])
                            ->where(['enabled' => 1, 'id' => ArrayHelper::getColumn($categoryIds, 'article_id')])
                            ->with(['articleCategories' => function($query) {
                                    return $query->select(['category_id', 'article_id']);
                            }])
                            ->orderBy(['created_at' => $order])
                            ->all();

        $articlesIds = ArrayHelper::getColumn($articles, 'id');

        $categoryCollection = Yii::createObject(CategoryCollection::class);
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
            'articleCount' => ArticleCategory::find()->where(['category_id' => $this->current->id])->count('id')
        ];
    }
    
    public function getTamplate() {
        return 'articles';
    }
}

