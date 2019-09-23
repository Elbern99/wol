<?php

namespace console\controllers;

use backend\helpers\ConsoleRunner;
use common\models\Article;
use common\models\Category;
use common\models\Newsletter;
use common\models\NewsletterLogs;
use common\modules\eav\Collection;
use common\modules\eav\helper\EavAttributeHelper;
use Yii;
use yii\console\Controller;

class AlertsController extends Controller
{
    /**
     * @param $articleId
     * @return bool|int
     * @throws \yii\base\InvalidConfigException
     */
    public function actionNewArticleAlerts($articleId)
    {
        $cmd = 'yii alerts/new-article-alerts' . $articleId;
        if (ConsoleRunner::isRun($cmd)) {
            Yii::warning('Console command "' . $cmd . '" already started.');
            return self::EXIT_CODE_ERROR;
        }

        return $this->send($articleId, 'article_newsletter.php', 'Article from IZA World of Labor');
    }

    /**
     * @param $articleId
     * @return bool|int
     * @throws \yii\base\InvalidConfigException
     */
    public function actionNewArticleVersionAlerts($articleId)
    {
        $cmd = 'yii alerts/new-article-version-alerts' . $articleId;
        if (ConsoleRunner::isRun($cmd)) {
            Yii::warning('Console command "' . $cmd . '" already started.');
            return self::EXIT_CODE_ERROR;
        }

        return $this->send($articleId, 'article_newsletter_version.php', 'A new version of an article published');
    }

    /**
     * @param $articleId
     * @param $viewFileName
     * @param $subject
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function send($articleId, $viewFileName, $subject)
    {
        $article = Article::findOne($articleId);
        if (!$article) {
            return false;
        }

        $articleCollection = Yii::createObject(Collection::class);
        $articleCollection->initCollection($article::ENTITY_NAME, $article, false);
        $attributes = $articleCollection->getEntity()->getValues();
        EavAttributeHelper::initEavAttributes($attributes);

        if (isset($attributes['full_pdf'])) {
            $pdfUrl = Yii::$app->frontendUrlManager->createAbsoluteUrl([$attributes['full_pdf']->getData('url')]);
        } else {
            $pdfUrl = null;
        }

        $event = new \common\modules\article\ArticleEvent();
        $event->id = $article->id;
        $event->title = $article->title;
        $event->url = Yii::$app->frontendUrlManager->createAbsoluteUrl(['article/one-pager', 'slug' => $article->seo]);
        $event->categoryIds = $article->getArticleCategories()->select('category_id')->column();
        $event->availability = $article->availability;
        $event->pdf = $pdfUrl;
        $event->version = $article->version;

        $categories = Category::find()->select([
            'id', 'root',
            'lvl', 'lft', 'rgt'])
            ->where(['id' => $event->categoryIds, 'active' => 1])
            ->all();

        $subscribers = Newsletter::find()->andWhere(['interest' => 1]);

        $filtered = [];
        $first = true;
        foreach ($categories as $category) {
            if ($category->lvl > 1) {
                $parent = $category->parents(1)->select(['lvl', 'lft', 'rgt', 'id'])->one();

                if (is_object($parent)) {

                    if (array_search($parent->id, $filtered) !== false) {
                        continue;
                    }

                    if ($first) {
                        $subscribers->andFilterWhere(['like', 'areas_interest', $parent->id]);
                        $first = false;
                    } else {
                        $subscribers->orFilterWhere(['like', 'areas_interest', $parent->id]);
                    }

                    $filtered[] = $parent->id;
                }
            } else {
                if (array_search($category->id, $filtered) !== false) {
                    continue;
                }

                if ($first) {
                    $subscribers->andFilterWhere(['like', 'areas_interest', $category->id]);
                    $first = false;
                } else {
                    $subscribers->orFilterWhere(['like', 'areas_interest', $category->id]);
                }

                $filtered[] = $category->id;
            }
        }

        $transaction = NewsletterLogs::getDb()->beginTransaction();
        $qtySubscribers = $subscribers->count();
        $oneSubscriberPercent = (100 / $qtySubscribers);

        $newsletterLog = new NewsletterLogs();
        $newsletterLog->progress = 0;
        $newsletterLog->status = NewsletterLogs::STATUS_IN_PROGRESS;
        $newsletterLog->subject = $subject;
        $newsletterLog->qty = $qtySubscribers;
        $newsletterLog->save(false);
        try {
            $newsletterLog->link('article', $article);

            $sendMessage = function ($subscriber) use ($viewFileName, $subject, $event) {
                return Yii::$app->mailer
                    ->compose('@backend/views/emails/' . $viewFileName, [
                        'subscriber' => $subscriber,
                        'article' => $event
                    ])
                    ->setFrom([Yii::$app->params['fromAddress'] => Yii::$app->params['fromName']])
                    ->setTo($subscriber->email)
                    ->setSubject($subject)
                    ->send();
            };

            foreach ($subscribers->each(200) as $subscriber) {
                /** @var Newsletter $subscriber */
                Yii::info('Send mail');
                $this->stdout('Send mail to ' . $subscriber->email);
                try {
                    $isMailSent = $sendMessage($subscriber);
                } catch (\Swift_TransportException $e) {
                    $isMailSent = false;
                    if ($e->getCode() == 421) {
                        $this->stdout('Client has exceeded the configured limit. Sleeping 10 seconds...');
                        sleep(10);
                        $this->stdout('Resend mail to ' . $subscriber->email);
                        $isMailSent = $sendMessage($subscriber);
                    }
                }

                if (!$isMailSent) {
                    $newsletterLog->status = NewsletterLogs::STATUS_WARNING;
                    $newsletterLog->error_text .= 'Mail to ' . $subscriber->email . ' has not been sent.';
                    $newsletterLog->save(false);
                } else {
                    $newsletterLog->link('subscribers', $subscriber);
                }
                $newsletterLog->progress += (int) $oneSubscriberPercent;
                $newsletterLog->save(false);
            }
            if ($newsletterLog->progress != 100) {
                $newsletterLog->progress = 100;
            }
            if ($newsletterLog->status == NewsletterLogs::STATUS_IN_PROGRESS) {
                $newsletterLog->status = NewsletterLogs::STATUS_SUCCESS;
            }
            $newsletterLog->save(false);
            $transaction->commit();
        } catch (\Throwable $e) {
            $newsletterLog->error_text = $e->getMessage();
            $newsletterLog->status = NewsletterLogs::STATUS_ERROR;
            $newsletterLog->save(false);
            $transaction->commit();
        }

        return true;
    }
}
