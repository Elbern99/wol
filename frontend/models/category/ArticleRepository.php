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
use yii\helpers\Html;
use common\modules\eav\helper\EavValueHelper;
use common\models\Category;
use common\modules\author\Roles;

class ArticleRepository implements RepositoryInterface {
    
    use \frontend\components\articles\SubjectTrait;
    
    private $current;
    
    public function __construct($currentCategory) {
        $this->current = $currentCategory;
    }
    
    private function getArticleIds($limit, $order) {
        
        return ArticleCategory::find()
                                ->alias('ac')
                                ->select(['article_id'])
                                ->innerJoin(Article::tableName().' as a', 'a.id = ac.article_id')
                                ->where(['ac.category_id' => $this->current->id, 'a.enabled' => 1])
                                ->orderBy(['created_at' => $order])
                                ->asArray()
                                ->limit($limit)
                                ->all();
    }
    
    private function getArticlesModel($categoryIds, $order) {
        
        return Article::find()
                        ->select(['id', 'title', 'seo', 'availability', 'created_at'])
                        ->where(['enabled' => 1, 'id' => ArrayHelper::getColumn($categoryIds, 'article_id')])
                        ->with(['articleCategories' => function($query) {
                                return $query->alias('ac')
                                     ->select(['category_id', 'article_id'])
                                     ->innerJoin(Category::tableName().' as c', 'ac.category_id = c.id AND c.lvl = 1');
                        }])
                        ->with(['articleAuthors.author' => function($query) {
                             return $query->select(['id','url_key', 'name'])->asArray();
                         }])
                        ->orderBy(['created_at' => $order])
                        ->all();
    }
    
    /* get Authors for category on top */
    private function getAuthorIds(array $roles) {
        
        return AuthorCategory::find()->alias('ac')->select(['ac.author_id'])
                               ->where(['category_id' => $this->current->id])
                               ->innerJoinWith(['authorRoles' => function($query) use ($roles) {
                                   return $query->where(['role_id' => $roles]);
                               }])
                               ->asArray()
                               ->all();
    
    }


    public function getPageParams() {
        
        $order = SORT_DESC;
        $authorRoles = new Roles();
        $editor = [
            'subject' => [],
            'associate' => []
        ];
        
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
        
        $categoryIds = $this->getArticleIds($limit, $order);
        
        $roles = [];
        $associateEditor = $authorRoles->getTypeByLabel('associateEditor');
        $subjectEditor = $authorRoles->getTypeByLabel('subjectEditor');
        array_push($roles, $subjectEditor);
        array_push($roles, $associateEditor);
        
        $authorRoleIds = $this->getAuthorIds($roles);
        
        if (count($authorRoleIds)) {
            
            $authorIds = ArrayHelper::getColumn($authorRoleIds, 'author_id');
            $authors = Author::find()
                                ->select(['id', 'avatar', 'name', 'url_key'])
                                ->where(['id' => $authorIds])
                                ->asArray()
                                ->all();

            $authors = ArrayHelper::map($authors, 'id', function($data) {
                return [
                    'avatar' => Author::getImageUrl($data['avatar']), 
                    'name' => $data['name'],
                    'profile' => Author::getAuthorUrl($data['url_key'])
                ];
            });

            $authorCollection = Yii::createObject(CategoryCollection::class);
            $authorCollection->setAttributeFilter(['affiliation']);
            $authorCollection->initCollection(Author::tableName(), $authorIds);
            $authorValues = $authorCollection->getValues();

            foreach ($authorRoleIds as $data) {

                $affiliation =  EavValueHelper::getValue($authorValues[$data['author_id']], 'affiliation', function($data) {
                    return $data->affiliation;
                }, 'string');
                    
                $params = $authors[$data['author_id']];
                $roles = ArrayHelper::getColumn($data['authorRoles'], 'role_id');

                if (in_array($subjectEditor, $roles)) {
                    $editor['subject'][] = [
                        'avatar' => $params['avatar'],
                        'profile' => $params['profile'],
                        'name' => $params['name'],
                        'affiliation' => $affiliation
                    ];
                } elseif (in_array($associateEditor, $roles)) {
                    $editor['associate'][] = [
                        'avatar' => $params['avatar'],
                        'profile' => $params['profile'],
                        'name' => $params['name'],
                        'affiliation' => $affiliation
                    ];
                }
                
            }
            
            unset($authors);
        }

        if (!count($categoryIds)) {
            
            return [
                'category' => $this->current, 
                'subjectAreas' => $subjectAreas, 
                'collection' => [], 
                'sort' => $order,
                'limit' => $limit,
                'articleCount' => 1,
                'editors' => $editor,
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
            $authors = [];
            
            foreach ($article->articleCategories as $c) {

                if (isset($categoryFormat[$c->category_id])) {

                    $articleCategory[] = '<a href="' . $categoryFormat[$c->category_id]['url_key'] . '" >' . $categoryFormat[$c->category_id]['title'] . '</a>';
                }
            }
            
            if (count($article->articleAuthors)) {
                
                foreach ($article->articleAuthors as $author) {
                    $authors[] = Html::a($author->author['name'], Author::getAuthorUrl($author->author['url_key']));
                }
            } else {
                $authors[] = $article->availability;
            }

            $eavValue = $values[$article->id] ?? [];
            
            $articlesCollection[$article->id] = [
                'title' => $article->title,
                'url' => '/articles/' . $article->seo,
                'authors' => $authors,
                'teaser' => EavValueHelper::getValue($eavValue, 'teaser', function($data) {
                    return $data;
                }),
                'abstract' => EavValueHelper::getValue($eavValue, 'abstract', function($data) {
                    return $data;
                }), 
                'created_at' => $article->created_at,
                'category' => $articleCategory,
            ];
        }
        
        $parent = null;
        
        if ($this->current->lvl > 1) {
            $parent = $this->current->parents(1)->select(['title', 'url_key'])->one();
        }
        
        return [
            'parentCategory' => $parent,
            'category' => $this->current, 
            'subjectAreas' => $subjectAreas, 
            'collection' => $articlesCollection, 
            'sort' => $order,
            'limit' => $limit,
            'articleCount' => $this->getCategoryArticleCount(),
            'editors' => $editor,
        ];
    }
    
    public function getCategoryArticleCount() {
        return ArticleCategory::find()
                ->alias('ac')
                ->innerJoin(Article::tableName().' as a', 'a.id = ac.article_id')
                ->where(['ac.category_id' => $this->current->id, 'a.enabled' => 1])
                ->count('a.id');
    }
    
    public function getTamplate() {
        return 'articles';
    }
}

