<?php


namespace common\modules\task\controllers;

use common\models\Article;
use common\models\Newsletter;
use common\modules\article\ArticleEvent;
use UrbanIndo\Yii2\Queue\Worker\Controller;
use yii\helpers\ArrayHelper;
use yii;

class ArticleAlertsController extends Controller
{
    public function actionNewArticleAlerts($articleId)
    {
        return $this->send($articleId, 'article_newsletter.php', 'Article from IZA World of Labor');
    }

    public function actionNewArticleVersionAlerts($articleId)
    {
        return $this->send($articleId, 'article_newsletter_version.php', 'A new version of an article published');
    }

    protected function send($articleId, $viewFileName, $subject)
    {
        $article = Article::findOne($articleId);
        if (!$article) {
            return false;
        }

        $categories = ArrayHelper::map($article->articleCategories, 'id', 'id');
        $subscribers = Newsletter::find()->select(['first_name', 'email', 'last_name', 'code'])->andWhere(['interest' => 1]);

        foreach ($categories as $categoryId) {
            $subscribers->andFilterWhere(['like', 'areas_interest', $categoryId]);
        }

        $subscribers = $subscribers->each(200);

        foreach ($subscribers as $subscriber) {
            /** @var Newsletter $subscriber */
            Yii::info('Send mail');
            Yii::$app->mailer
                ->compose('@backend/views/emails/' . $viewFileName, [
                    'subscriber' => $subscriber,
                    'article' => $article
                ])
                ->setFrom([Yii::$app->params['fromAddress'] => Yii::$app->params['fromName']])
                ->setTo($subscriber->email)
                ->setSubject($subject)
                ->send();
        }

        return true;
    }
}
