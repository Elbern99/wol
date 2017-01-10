<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\Topic;
use common\models\Category;
use common\modules\eav\CategoryCollection;
use common\models\Article;
use common\modules\eav\Collection;
/**
 * Site controller
 */
class TopicController extends Controller {
    
    use \frontend\components\articles\SubjectTrait;
    use \frontend\components\articles\ArticleTrait;
    
    protected function _getMainCategory() 
    {
        $category = Category::find()->where([
            'url_key' => 'key-topics',
        ])->one();
        return $category;
    }
    
    public function _getTopicsList($limit = null)
    {
        $query = (new \yii\db\Query())
        ->select('*')
        ->from('topics')
        ->where('sticky_at is null')
        ->orderBy('created_at desc');
        
        $topics = Topic::find()
                            ->where('sticky_at is not null')
                            ->orderBy('sticky_at asc')
                            ->union($query)
                            ->limit($limit)
                            ->all();
        
        return array_slice($topics, 0, $limit);
    }
    
    public function actionIndex()
    {
        $limit = Yii::$app->params['topic_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }
         
        $topicsQuery = Topic::find()->orderBy('id desc');

        return $this->render('index', [
            'category' => $this->_getMainCategory(),
            'topics' => $this->_getTopicsList($limit),
            'topicsCount' => $topicsQuery->count(),
            'limit' => $limit,
        ]);
    }
    
    public function actionView($slug = null)
    {
        if (!$slug)
            return $this->goHome();
        
        $topic = Topic::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$topic)
            return $this->redirect(Url::to(['/topic/index']));     
        
        $relatedVideos = $topic->getTopicVideos()->limit(Yii::$app->params['topic_videos_limit'])->all();
        $relatedOpinions = $topic->getTopicOpinions()->limit(Yii::$app->params['topic_opinions_limit'])->all();
        $relatedArticles = $topic->getTopicArticles()->limit(Yii::$app->params['topic_articles_limit'])->all();
        $relatedEvents = $topic->getTopicEvents()->limit(Yii::$app->params['topic_events_limit'])->all();
        
        $keyTopics = Topic::find()->where([
            'is_key_topic' => true,
        ])->orderBy('id desc')->all();
        
        $order = SORT_DESC;

        if (Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
        }
        
        $category = $this->getMainArticleCategory();
        $subjectAreas = $this->getSubjectAreas($category);
        
        $categoryFormat = ArrayHelper::map($subjectAreas, 'id', function($data) {
            return ['title'=>$data['title'], 'url_key'=>$data['url_key']];
        });
        
        $articles = [];
        
        foreach ($relatedArticles as $item) {
            $articles[] = $item->article;
        }
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

                    $articleCategory[] = '<a href="'.$categoryFormat[$c->category_id]['url_key'].'" >'.$categoryFormat[$c->category_id]['title'].'</a>';
                }
            }
            
            $articlesCollection[$article->id] = [
                'title' => $article->title,
                'url' => '/articles/'.$article->seo,
                'availability' => $article->availability,
                'teaser' => unserialize($values[$article->id]['teaser']),
                'abstract' => unserialize($values[$article->id]['abstract']), 
                'created_at' => $article->created_at,
                'category' => $articleCategory,
            ];
        }
        
        
        return $this->render('view', [
            'model' => $topic,
            'category' => $this->_getMainCategory(),
            'relatedVideos' => $relatedVideos,
            'relatedOpinions' => $relatedOpinions,
            'collection' => $articlesCollection,
            'relatedEvents' => $relatedEvents, 
            'relatedVideosCount' => $topic->getTopicVideos()->count(),
            'relatedOpinionsCount' => $topic->getTopicOpinions()->count(),
            'relatedArticlesCount' => $topic->getTopicArticles()->count(),
            'relatedEventsCount' => $topic->getTopicEvents()->count(),
            'keyTopics' => $keyTopics,
            'sort' => $order,
        ]);

    }
    
    public function actionEvents($topic_id = null)
    {
        $topic = Topic::find()->andWhere(['id' => $topic_id])->one();
 
        if (!$topic)
            return $this->redirect(Url::to(['/topic/index']));     

        $eventLimit =  Yii::$app->params['topic_events_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('event_limit')) {
                $limitPrev = Yii::$app->request->get('event_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $eventLimit += (int)$limitPrev;
                }
            }
           
        }
        else {
            return $this->redirect(['/topic/index']);
        }
        
        return $this->renderAjax('_events', [
            'events' => $topic->getTopicEvents()->limit($eventLimit)->all(),
            'eventLimit' => $eventLimit,
            'eventsCount' => $topic->getTopicEvents()->count(),
            'topicId' => $topic_id,
        ]);
    }
    
    public function actionVideos($topic_id = null)
    {
        $topic = Topic::find()->andWhere(['id' => $topic_id])->one();
 
        if (!$topic)
            return $this->redirect(Url::to(['/topic/index']));     

        $videoLimit =  Yii::$app->params['topic_videos_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('video_limit')) {
                $limitPrev = Yii::$app->request->get('video_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $videoLimit += (int)$limitPrev;
                }
            }
           
        }
        else {
            return $this->redirect(['/topic/index']);
        }
        
        return $this->renderAjax('_videos', [
            'videos' => $topic->getTopicVideos()->limit($videoLimit)->all(),
            'videoLimit' => $videoLimit,
            'videosCount' => $topic->getTopicVideos()->count(),
            'topicId' => $topic_id,
        ]);
         
    }
    
    public function actionOpinions($topic_id = null)
    {
        $topic = Topic::find()->andWhere(['id' => $topic_id])->one();
 
        if (!$topic)
            return $this->redirect(Url::to(['/topic/index']));     

        $opinionLimit =  Yii::$app->params['topic_opinions_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('opinion_limit')) {
                $limitPrev = Yii::$app->request->get('opinion_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $opinionLimit += (int)$limitPrev;
                }
            }
           
        }
        else {
            return $this->redirect(['/topic/index']);
        }
        
        return $this->renderAjax('_opinions', [
            'opinions' => $topic->getTopicOpinions()->limit($opinionLimit)->all(),
            'opinionLimit' => $opinionLimit,
            'opinionsCount' => $topic->getTopicOpinions()->count(),
            'topicId' => $topic_id,
        ]);
         
    }
    
    
    public function actionArticles($topic_id = null)
    {
        $topic = Topic::find()->andWhere(['id' => $topic_id])->one();
 
        if (!$topic)
            return $this->redirect(Url::to(['/topic/index']));     

        $articleLimit =  Yii::$app->params['topic_articles_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('article_limit')) {
                $limitPrev = Yii::$app->request->get('article_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $articleLimit += (int)$limitPrev;
                }
            }
           
        }
        else {
            return $this->redirect(['/topic/index']);
        }
        
        $relatedArticles = $topic->getTopicArticles()->limit($articleLimit)->all();
        
        $order = SORT_DESC;

        if (Yii::$app->request->get('sort')) {
            $order = SORT_ASC;
        }
        
        $category = $this->getMainArticleCategory();
        $subjectAreas = $this->getSubjectAreas($category);
        
        $categoryFormat = ArrayHelper::map($subjectAreas, 'id', function($data) {
            return ['title'=>$data['title'], 'url_key'=>$data['url_key']];
        });
        
        $articles = [];
        
        foreach ($relatedArticles as $item) {
            $articles[] = $item->article;
        }
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

                    $articleCategory[] = '<a href="'.$categoryFormat[$c->category_id]['url_key'].'" >'.$categoryFormat[$c->category_id]['title'].'</a>';
                }
            }
            
            $articlesCollection[$article->id] = [
                'title' => $article->title,
                'url' => '/articles/'.$article->seo,
                'availability' => $article->availability,
                'teaser' => unserialize($values[$article->id]['teaser']),
                'abstract' => unserialize($values[$article->id]['abstract']), 
                'created_at' => $article->created_at,
                'category' => $articleCategory,
            ];
        }
        
        return $this->renderAjax('_articles', [
            'collection' => $articlesCollection,
            'articleLimit' => $articleLimit,
            'articlesCount' => $topic->getTopicArticles()->count(),
            'topicId' => $topic_id,
            'sort' => $order,
        ]);
         
    }
}