<?php


namespace common\modules\task\controllers;

use common\models\Article;
use common\models\Newsletter;
use UrbanIndo\Yii2\Queue\Worker\Controller;
use yii\helpers\ArrayHelper;
use yii;

class ArticleAlertsController extends Controller
{
    public function actionNewArticleAlerts($articleId)
    {
        return $this->send($articleId, 'article_newsletter.php');
    }

    public function actionNewArticleVersionAlerts($articleId)
    {
        return $this->send($articleId, 'article_newsletter_version.php');
    }

    protected function send($articleId, $viewFileName)
    {
        $article = Article::findOne($articleId);
        if (!$article) {
            return false;
        }

        $categories = ArrayHelper::map($article->articleCategories, 'id', 'id');
        $subscribers = Newsletter::find()->select(['first_name', 'email', 'last_name', 'code'])->andWhere(['interest' => 1]);

        foreach ($categories as $category) {
            $subscribers->andFilterWhere(['like', 'areas_interest', $category->id]);
        }

        $subscribers = $subscribers->each(200);

        foreach ($subscribers as $subscriber) {
            //TODO send email to each subscribe
        }

        return true;
    }
}