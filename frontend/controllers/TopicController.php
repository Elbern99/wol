<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\Url;

use common\models\Topic;
use common\models\TopicOpinion;
/**
 * Site controller
 */
class TopicController extends Controller {
    
    use \frontend\controllers\traits\TopicTrait;
    
    protected $key = 'key-topics';

    public function actionIndex()
    {
        $limit = Yii::$app->params['topic_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }

        }

        return $this->render('index', [
            'category' => $this->getMainCategory(),
            'topics' => $this->getTopicsList($limit),
            'topicsCount' => $this->getTopicsCount(),
            'limit' => $limit,
        ]);
    }
    
    public function actionView($slug)
    {
        $topic = Topic::find()->andWhere(['enabled' => 1])->andWhere(['url_key' => $slug])->one();
        
        if (!$topic) {
            throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
        }

        $relatedVideos = $topic->getTopicVideos()->with('video')->limit(Yii::$app->params['topic_videos_limit'])->all();
        $relatedOpinions = TopicOpinion::find()
                                        ->with(['opinion' => function($query) {
                                            return $query->select(['id', 'image_link', 'url_key', 'title'])->where(['enabled' => 1])->with('opinionAuthors');
                                        }])
                                        ->where(['topic_id' => $topic->id])
                                        ->limit(Yii::$app->params['topic_opinions_limit'])
                                        ->all();
        
        $relatedEvents = $topic->getTopicEvents()->with(['event' => function($query) {
            return $query->where(['enabled' => 1]);
        }])->limit(Yii::$app->params['topic_events_limit'])->all();

        $keyTopics = Topic::find()->andWhere(['enabled' => 1])->where([
            'is_key_topic' => true,
        ])->andWhere(['=', 'is_hided', 0])
        ->orderBy('id desc')->all();

        return $this->render('view', [
            'model' => $topic,
            'category' => $this->getMainCategory(),
            'relatedVideos' => $relatedVideos,
            'relatedOpinions' => $relatedOpinions,
            'collection' => $this->getTopicArticles($topic, Yii::$app->params['topic_articles_limit']),
            'relatedEvents' => $relatedEvents, 
            'relatedVideosCount' => $topic->getTopicVideos()->count(),
            'relatedOpinionsCount' => $this->getTopicOpinionsCount($topic),
            'relatedArticlesCount' => $this->getTopicArticlesCount($topic),
            'relatedEventsCount' => $this->getTopicEventsCount($topic),
            'keyTopics' => $keyTopics,
        ]);

    }

    public function actionEvents($topic_id = null)
    {
        $topic = Topic::find()->andWhere(['enabled' => 1])->andWhere(['id' => $topic_id])->one();
 
        if (!$topic) {
            return $this->redirect(Url::to(['/topic/index'])); 
        }
                
        $eventLimit =  Yii::$app->params['topic_events_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            
            if (Yii::$app->request->get('event_limit')) {
                $limitPrev = Yii::$app->request->get('event_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $eventLimit += (int)$limitPrev;
                }
            }
           
        } else {
            return $this->redirect(['/topic/index']);
        }
        
        return $this->renderAjax('_events', [
            'events' => $topic->getTopicEvents()->with(['event' => function($query) {
                return $query->where(['enabled' => 1]);
            }])->limit($eventLimit)->all(),
            'eventLimit' => $eventLimit,
            'eventsCount' => $this->getTopicEventsCount($topic),
            'topicId' => $topic_id,
        ]);
    }
    
    public function actionVideos($topic_id = null)
    {
        $topic = Topic::find()->andWhere(['enabled' => 1])->andWhere(['id' => $topic_id])->one();
 
        if (!$topic) {
            return $this->redirect(Url::to(['/topic/index']));
        }

        $videoLimit =  Yii::$app->params['topic_videos_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            
            if (Yii::$app->request->get('video_limit')) {
                $limitPrev = Yii::$app->request->get('video_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $videoLimit += (int)$limitPrev;
                }
            }
           
        } else {
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
        $topic = Topic::find()->andWhere(['enabled' => 1])->andWhere(['id' => $topic_id])->one();
 
        if (!$topic) {
            return $this->redirect(Url::to(['/topic/index']));
        }

        $opinionLimit =  Yii::$app->params['topic_opinions_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            
            if (Yii::$app->request->get('opinion_limit')) {
                $limitPrev = Yii::$app->request->get('opinion_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $opinionLimit += (int)$limitPrev;
                }
            }
           
        } else {
            return $this->redirect(['/topic/index']);
        }
        
        $opinions = TopicOpinion::find()
                                ->with(['opinion' => function($query) {
                                    return $query->select(['id', 'image_link', 'url_key', 'title'])->where(['enabled' => 1])->with('opinionAuthors');
                                }])
                                ->where(['topic_id' => $topic->id])
                                ->limit($opinionLimit)
                                ->all();
                                
        return $this->renderAjax('_opinions', [
            'opinions' => $opinions,
            'opinionLimit' => $opinionLimit,
            'opinionsCount' => $this->getTopicOpinionsCount($topic),
            'topicId' => $topic_id,
        ]);
         
    }
    
    
    public function actionArticles($topic_id = null)
    {
        $topic = Topic::find()->andWhere(['enabled' => 1])->andWhere(['id' => $topic_id])->one();
 
        if (!$topic) {
            return $this->redirect(Url::to(['/topic/index']));
        }

        $articleLimit =  Yii::$app->params['topic_articles_limit'];
        
        if (Yii::$app->request->getIsPjax()) {
            if (Yii::$app->request->get('article_limit')) {
                $limitPrev = Yii::$app->request->get('article_limit');

                if (isset($limitPrev) && intval($limitPrev)) {
                    $articleLimit += (int)$limitPrev;
                }
            }
           
        } else {
            return $this->redirect(['/topic/index']);
        }

        return $this->renderAjax('_articles', [
            'collection' => $this->getTopicArticles($topic, $articleLimit),
            'articleLimit' => $articleLimit,
            'articlesCount' => $this->getTopicArticlesCount($topic),
            'topicId' => $topic_id,
        ]);
         
    }
}