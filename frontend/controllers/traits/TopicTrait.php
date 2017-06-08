<?php
namespace frontend\controllers\traits;

use Yii;

use common\models\Category;
use common\models\Topic;
use common\models\Opinion;
use common\models\Article;
use common\models\Event;
use common\modules\eav\CategoryCollection;
use common\models\Author;
use common\modules\eav\helper\EavValueHelper;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

trait TopicTrait {
    
    use \frontend\components\articles\SubjectTrait;
    
    protected function getMainCategory() {
        
        return Category::find()->where([
            'url_key' => $this->key,
        ])
        ->one();
    }
    
    protected function getTopicsList($limit = null) {
        
        $topics = Topic::find()
                            ->andWhere(['=', 'is_hided', 0])
                            ->andWhere(['enabled' => 1])
                            ->orderBy(['sticky_at' => SORT_DESC, 'created_at' => SORT_DESC])
                            ->asArray();
        
        if ($limit) {
            $topics->limit($limit);
        }

        return $topics->all();
    }
    
    protected function getTopicsCount() {
        return Topic::find()->andWhere(['enabled' => 1])->andWhere(['=', 'is_hided', 0])->count();
    }
    
    protected function getTopicOpinionsCount(Topic $topic) {
        
        return $topic->getTopicOpinions()
                        ->alias('to')
                        ->innerJoin(['o' => Opinion::tableName()], 'o.id = to.opinion_id')
                        ->where(['o.enabled' => 1])
                        ->count();
    }
    
    protected function getTopicEventsCount(Topic $topic) {
        
        return $topic->getTopicEvents()
                        ->alias('te')
                        ->innerJoin(['e' => Event::tableName()], 'e.id = te.event_id')
                        ->where(['e.enabled' => 1])
                        ->count(); 
    }
    
    protected function getArticlesModel($articlesIds, $limit) {

        return  Article::find()
                        ->alias('a')
                        ->select(['a.id', 'a.title', 'a.seo', 'a.availability', 'a.created_at'])
                        ->where(['a.enabled' => 1, 'a.id' => $articlesIds])
                        ->with(['articleCategories' => function($query) {
                                return $query->alias('ac')
                                     ->select(['category_id', 'article_id'])
                                     ->innerJoin(Category::tableName().' as c', 'ac.category_id = c.id AND c.lvl = 1');
                        }])
                        ->with(['articleAuthors.author' => function($query) {
                            return $query->select(['id','url_key', 'name'])->asArray();
                        }])
                        ->orderBy(['a.created_at' => SORT_DESC, 'a.id' => SORT_ASC])
                        ->limit($limit)
                        ->all();
    }
    
    protected function getTopicArticles(Topic $topic, $limit): array {
        
        $topicArticles = $this->getTopicArticlesQuery($topic)->asArray()->all();
        $articlesIds = ArrayHelper::getColumn($topicArticles, 'article_id');
        $articles = $this->getArticlesModel($articlesIds, $limit);
        
        $categoryFormat = ArrayHelper::map($this->getSubjectAreas(), 'id', function($data) {
            return ['title' => $data['title'], 'url_key' => $data['url_key']];
        });
        
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

                    $articleCategory[] = '<a href="'.$categoryFormat[$c->category_id]['url_key'].'" >'.$categoryFormat[$c->category_id]['title'].'</a>';
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
                'url' => '/articles/'.$article->seo,
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
    
    protected function getTopicArticlesCount(Topic $topic) {
        return $this->getTopicArticlesQuery($topic)->count();
    }
    
    protected function getTopicArticlesQuery(Topic $topic) {
        
        return $topic->getTopicArticles()
                        ->alias('ta')
                        ->innerJoin(['a' => Article::tableName()], 'a.id = ta.article_id')
                        ->where(['a.enabled' => 1]);
    }

}