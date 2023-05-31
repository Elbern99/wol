<?php

namespace frontend\controllers\traits;

use frontend\models\CmsPagesRepository as Page;
use common\models\CmsPages;
use yii\httpclient\Client;
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
use common\models\HomepageCommentary;
use common\models\Video;
use common\models\Opinion;

trait HomeTrait
{

    use \frontend\components\articles\SubjectTrait;
    use \frontend\components\articles\ArticleTrait;

    protected $more;

    protected function getHomeParams()
    {
        $this->getRssNews();
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
//            'news' => $this->getNews()
            'news' => $this->getRssNews()
        ];
    }

    protected function getCommentary()
    {

        $commentary = HomepageCommentary::find()->all();
        $videoList = [];
        $opinionList = [];

        foreach ($commentary as $object) {

            if ($object->type == Video::class) {
                $videoList[] = $object->object_id;
            } else if ($object->type == Opinion::class) {
                $opinionList[] = $object->object_id;
            }
        }


        $params = [
            'step' => Yii::$app->params['commentary_video_limit'],
            'count' => count($videoList),
            'first_step' => 3
        ];

        $this->more->addParam($params, 'commentary_video_limit');
        $limit = $this->more->getLimit('commentary_video_limit');

        $videos = Video::find()
            ->select(['id', 'url_key', 'title', 'video'])
            ->where(['id' => $videoList])
            ->limit($limit)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $params = [
            'step' => Yii::$app->params['commentary_opinion_limit'],
            'count' => count($opinionList),
            'first_step' => 3
        ];

        $this->more->addParam($params, 'commentary_opinion_limit');
        $limit = $this->more->getLimit('commentary_opinion_limit');

        $opinions = Opinion::find()
            ->select(['id', 'url_key', 'image_link', 'title'])
            ->limit($limit)
            ->with(['opinionAuthors' => function ($query) {
                return $query->select(['opinion_id', 'author_name', 'author_url'])->orderBy('author_order')->asArray();
            }])
            ->where(['id' => $opinionList])
            ->andWhere(['enabled' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $collection = [
            'video' => $videos ?? [],
            'opinion' => $opinions ?? [],
        ];

        return $collection;
    }

    protected function getNews()
    {

        $params = [
            'step' => Yii::$app->params['home_news_limit'],
            'count' => NewsItem::find()->count('id'),
            'first_step' => 4
        ];

        $this->more->addParam($params, 'news_limit');
        $limit = $this->more->getLimit('news_limit');

        return NewsItem::find()
            ->select(['title', 'url_key', 'created_at', 'image_link', 'short_description', 'sources'])
            ->limit($limit)
            ->orderBy(['created_at' => SORT_DESC])
            ->andWhere(['enabled' => 1])
            ->asArray()
            ->all();
    }

    protected function getTopics()
    {

        return Topic::find()
            ->select(['image_link', 'title', 'url_key'])
            ->where(['not', ['sticky_at' => null]])
            ->andWhere(['=', 'is_hided', 0])
            ->orderBy(['sticky_at' => SORT_DESC])
            ->andWhere(['enabled' => 1])
            ->asArray()
            ->all();
    }

    protected function getLastEvents()
    {

        $params = [
            'step' => Yii::$app->params['home_event_limit'],
            'count' => Event::find()->where('date_from >= now()')->count('id'),
        ];

        $this->more->addParam($params, 'event_limit');
        $limit = $this->more->getLimit('event_limit');

        return Event::find()
            ->select(['title', 'url_key', 'date_from', 'date_to', 'location', 'short_description', 'image_link'])
            ->andWhere('date_from >= now()')
            ->andWhere(['enabled' => 1])
            ->limit($limit)
            ->orderBy(['date_from' => SORT_ASC])
            ->asArray()
            ->all();

    }

    protected function getLastArticles($subjectAreas)
    {

        $params = [
            'step' => Yii::$app->params['home_article_limit'],
            'count' => $this->getArticleCount(),
        ];

        $this->more->addParam($params, 'article_limit');
        $limit = $this->more->getLimit('article_limit');

        $categoryFormat = ArrayHelper::map($subjectAreas, 'id', function ($data) {
            return ['title' => $data['title'], 'url_key' => $data['url_key']];
        });

        $articles = $this->getLastArticlesList($limit);

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
                'teaser' => EavValueHelper::getValue($eavValue, 'teaser', function ($data) {
                    return $data;
                }),
                'abstract' => EavValueHelper::getValue($eavValue, 'abstract', function ($data) {
                    return $data;
                }),
                'created_at' => $article->created_at,
                'category' => $articleCategory,
                'isShowLabel' => $article->isShowVersionLabel()
            ];
        }

        return $articlesCollection;
    }

    protected function getRssNews()
    {
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport' //только cURL поддерживает нужные нам параметры
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://newsroom.iza.org/en/latestnews/')
            ->setOptions([
                CURLOPT_CONNECTTIMEOUT => 5, // тайм-аут подключения
                CURLOPT_TIMEOUT => 10, // тайм-аут получения данных
            ])
            ->send();
        $xml = simplexml_load_string($response->getContent(), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        $news = [];
        if (!empty($array['channel']['item'])) {
            foreach ($array['channel']['item'] as $k => $v) {
                $news[$k] = $v;
                if ($k >= 4) {
                    break;
                }
            }
        }

//        echo "<pre>";
//        print_r($array);echo "</pre>";
//        die();
        return $news;
    }
}

