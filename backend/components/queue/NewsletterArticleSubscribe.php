<?php

namespace backend\components\queue;


use Yii;
use common\models\Newsletter;
use common\models\Category;


class NewsletterArticleSubscribe
{


    public static function addQueue($event)
    {

        if (!is_array($event->categoryIds) || !count($event->categoryIds)) {
            return false;
        }

        $subscribers = Newsletter::find()->select(['first_name', 'email', 'last_name', 'code'])->andWhere(['interest' => 1]);
        $categories = Category::find()->select([
                'id', 'root',
                'lvl', 'lft', 'rgt'])
            ->where(['id' => $event->categoryIds, 'active' => 1])
            ->all();

        $filered = [];
        $first = true;

        foreach ($categories as $category) {

            if ($category->lvl > 1) {

                $parent = $category->parents(1)->select(['lvl', 'lft', 'rgt', 'id'])->one();

                if (is_object($parent)) {

                    if (array_search($parent->id, $filered) !== false) {
                        continue;
                    }

                    if ($first) {
                        $subscribers->andFilterWhere(['like', 'areas_interest', $parent->id]);
                        $first = false;
                    } else {
                        $subscribers->orFilterWhere(['like', 'areas_interest', $parent->id]);
                    }

                    $filered[] = $parent->id;
                }
            } else {

                if (array_search($category->id, $filered) !== false) {
                    continue;
                }

                if ($first) {
                    $subscribers->andFilterWhere(['like', 'areas_interest', $category->id]);
                    $first = false;
                } else {
                    $subscribers->orFilterWhere(['like', 'areas_interest', $category->id]);
                }

                $filered[] = $category->id;
            }
        }

        $subscribers = $subscribers->all();

        foreach ($subscribers as $subscriber) {

            $body = Yii::$app->view->renderFile('@backend/views/emails/article_newsletter.php', ['subscriber' => $subscriber, 'article' => $event]);

            $job = new \UrbanIndo\Yii2\Queue\Job([
                'route' => 'mail/send',
                'data' => [
                    'to' => $subscriber->email,
                    'from' => Yii::$app->params['supportEmail'],
                    'subject' => 'Article from IZA World of Labor',
                    'body' => $body,
                    'article_id' => $event->id
                ]
            ]);

            Yii::$app->queue->post($job);
        }
    }
}
