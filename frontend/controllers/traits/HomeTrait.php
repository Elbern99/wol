<?php
namespace frontend\controllers\traits;

use frontend\models\CmsPagesRepository as Page;
use common\models\CmsPages;
use yii\web\NotFoundHttpException;
use Yii;
use common\modules\eav\helper\EavValueHelper;
use yii\helpers\ArrayHelper;
use common\modules\eav\CategoryCollection;
use common\models\Author;
use common\models\Article;
use yii\helpers\Html;
use frontend\components\helpers\ShowMore;
use common\models\Event;
use common\models\Topic;
use common\models\NewsItem;

trait HomeTrait {
    
    use \frontend\components\articles\SubjectTrait;
    use \frontend\components\articles\ArticleTrait;
    
    protected $more;
    
    protected function getHomeParams() {
        
        $homePage = CmsPages::find()->select('id')->where(['url' => 'home', 'enabled' => 1])->one();

        if (!is_object($homePage)) {
            throw new NotFoundHttpException();
        }
        
        $homePage = Page::getPageById($homePage->id);
        $subjectAreas = $this->getSubjectAreas();
        $this->more = new ShowMore();
        $articles = $this->getLastArticles($subjectAreas);
        
        return [
            'page' => $homePage, 
            'subjectAreas' => $subjectAreas,
            'collection' => $articles,
            'more' => $this->more,
            'events' => $this->getLastEvents(),
            'topics' => $this->getTopics(),
            'commentary' => $this->getCommentary(),
            'news' => $this->getNews()
        ];
    }
    
    protected function getCommentary() {
        return [];
    }
    
    protected function getNews() {
        
        $params = [
            'step' => Yii::$app->params['home_news_limit'],
            'count' => NewsItem::find()->count('id'),
        ];
        
        $this->more->addParam($params, 'news_limit');
        $limit = $this->more->getLimit('news_limit');
        
        return NewsItem::find()
                ->select(['title', 'url_key', 'created_at', 'image_link', 'short_description', 'editor'])
                ->limit($limit)
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->all();
    }
    
    protected function getTopics() {
        
        return  Topic::find()
                        ->select(['image_link', 'title', 'url_key'])
                        ->where(['not', ['sticky_at' => null]])
                        ->orderBy(['sticky_at' => SORT_DESC])
                        ->asArray()
                        ->all();
    }
    
    protected function getLastEvents() {
        
        $params = [
            'step' => Yii::$app->params['home_event_limit'],
            'count' => Event::find()->count('id'),
        ];
        
        $this->more->addParam($params, 'event_limit');
        $limit = $this->more->getLimit('event_limit');
        
        return Event::find()
                ->select(['title', 'url_key', 'date_from', 'date_to', 'location'])
                ->limit($limit)
                ->orderBy(['date_from' => SORT_DESC])
                ->asArray()
                ->all();

    }
    
    protected function getLastArticles($subjectAreas) {
        
        $params = [
            'step' => Yii::$app->params['home_article_limit'],
            'count' => $this->getArticleCount(),
        ];
        
        $this->more->addParam($params, 'article_limit');
        $limit = $this->more->getLimit('article_limit');
        
        $categoryFormat = ArrayHelper::map($subjectAreas, 'id', function($data) {
            return ['title'=>$data['title'], 'url_key'=>$data['url_key']];
        });

        $articles = $this->getArticlesList($limit, SORT_DESC);
        
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

                    $articleCategory[] = '<a href="'.$categoryFormat[$c->category_id]['url_key'].'" >'.$categoryFormat[$c->category_id]['title'].'</a>';
                }
            }

            if (count($article->articleAuthors)) {
                
                foreach ($article->articleAuthors as $author) {
                    $authors[] = Html::a($author->author['name'],Author::getAuthorUrl($author->author['url_key']));
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
}

