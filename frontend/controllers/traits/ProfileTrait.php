<?php
namespace frontend\controllers\traits;

use Yii;
use common\models\ArticleAuthor;
use common\models\Article;
use common\models\Author;
use yii\helpers\ArrayHelper;
use common\modules\eav\CategoryCollection;
use common\modules\eav\helper\EavValueHelper;
use common\models\AuthorRoles;
use common\modules\author\Roles;
use frontend\components\widget\SidebarWidget;
use common\models\Category;

trait ProfileTrait {
    
    use \frontend\components\articles\SubjectTrait;
    
    protected $subjectAreas = [];
    
    protected function getAuthorArticles(int $authorId): array {
        
        $articlesCollection = [];
        
        $articles = Article::find()
                        ->alias('a')
                        ->select(['a.id', 'a.title', 'a.seo', 'a.created_at'])
                        ->innerJoinWith(['articleAuthors.author' => function($query) {
                            return $query->alias('au')->select(['au.url_key', 'au.name'])->where(['au.enabled' => 1]);
                        }])
                        ->where(['a.enabled' => 1, 'au.id' => $authorId])
                        ->with(['articleCategories' => function($query) {
                                return $query->alias('ac')
                                     ->select(['category_id', 'article_id'])
                                     ->innerJoin(Category::tableName().' as c', 'ac.category_id = c.id AND c.lvl = 1');
                        }])
                        ->orderBy(['created_at' => SORT_DESC])
                        ->all();
        
        $this->subjectAreas = $this->getSubjectAreas();
        
        if (!count($articles)) {
            return $articlesCollection;
        }
                        
        $articlesIds = ArrayHelper::getColumn($articles, 'id');
        $categoryCollection = Yii::createObject(CategoryCollection::class);
        $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
        $categoryCollection->initCollection(Article::tableName(), $articlesIds);
        $values = $categoryCollection->getValues();

        $categoryFormat = ArrayHelper::map($this->subjectAreas, 'id', function($data) {
            return ['title' => $data['title'], 'url_key' => $data['url_key']];
        });
        
        foreach ($articles as $article) {

            $articleCategory = [];
            $authors = [];
            
            foreach ($article->articleAuthors as $author) {
                $authors[] = $author['author'];
            }

            foreach ($article->articleCategories as $c) {

                if (isset($categoryFormat[$c->category_id])) {

                    $articleCategory[] = '<a href="' . $categoryFormat[$c->category_id]['url_key'] . '" >' . $categoryFormat[$c->category_id]['title'] . '</a>';
                }
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
        
        return $articlesCollection;
    }
    
    protected function findAuthorsByLetter(string $letter, $type = null):array {
        
        $roles = new Roles();
        
        switch($type) {
            case 'expert':
                $filterRole = $roles->getExpertGroup();
                break;
            case 'editor':
                $filterRole = $roles->getEditorGroup();
                break;
            default:
                $filterRole = $roles->getAuthorGroup();
        }

        return  Author::find()
                        ->alias('a')
                        ->innerJoin(AuthorRoles::tableName().' as ar', 'ar.author_id = a.id')
                        ->select(['a.name', 'a.url_key'])
                        ->where(['a.enabled' => 1, 'ar.role_id' => $filterRole])
                        ->andFilterWhere(['like', 'a.surname', $letter.'%', false])
                        ->orderBy('a.surname')
                        ->asArray()
                        ->all();
    }
    
    protected function getProfileTemplate($type) {
        
        switch($type) {
            case 'expert':
                return 'profile-expert';
            case 'editor':
                return 'profile-editor';
            default:
                return 'profile';
        }
    }
    
    protected function renderProfile($url_key, $type = null) {
        
        $author = Author::find()
                ->where(['url_key' => $url_key, 'enabled' => 1])
                ->one();

        if (!is_object($author)) {
            throw new NotFoundHttpException('Page Not Found.');
        }

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->initCollection(Author::tableName(), $author->id);
        $authorValues = $authorCollection->getValues();

        $data = [
            'author' => $author,
            'country' => EavValueHelper::getValue($authorValues[$author->id], 'author_country', function($data){ return $data->code; }, 'array'),
            'testimonial' => EavValueHelper::getValue($authorValues[$author->id], 'testimonial', function($data) {
                        return $data->testimonial;
                    }, 'string'),
            'publications' => EavValueHelper::getValue($authorValues[$author->id], 'publications', function($data) {
                        return $data->publication;
                    }, 'array'),
            'affiliation' => EavValueHelper::getValue($authorValues[$author->id], 'affiliation', function($data) {
                        return $data->affiliation;
                    }, 'string'),
            'position' => EavValueHelper::getValue($authorValues[$author->id], 'position', function($data) {
                        return $data;
                    }),
            'degree' => EavValueHelper::getValue($authorValues[$author->id], 'degree', function($data) {
                        return $data->degree;
                    }, 'string'),
            'interests' => EavValueHelper::getValue($authorValues[$author->id], 'interests', function($data) {
                        return $data->interests;
                    }, 'string'),
            'expertise' => EavValueHelper::getValue($authorValues[$author->id], 'expertise', function($data) { return $data->expertise; }, 'array'),
            'experience_type' => EavValueHelper::getValue($authorValues[$author->id], 'experience_type', function($data) {
                        return ucfirst($data->expertise_type);
                    }, 'string'),
            'language' => EavValueHelper::getValue($authorValues[$author->id], 'language', function($data){ return $data; }, 'array'),
            //'experience_url' => EavValueHelper::getValue($authorValues[$author->id], 'experience_url', function($data) { return $data; }, 'array'),
            'roles' => $author->getAuthorRoles($type),
            'articles' => $this->getAuthorArticles($author->id)
        ];

        $widgets = new SidebarWidget('profile');

        return $this->render($this->getProfileTemplate($type), ['author' => $data, 'subjectAreas' => $this->subjectAreas, 'widgets' => $widgets, 'type' => $type]);
    }
}

