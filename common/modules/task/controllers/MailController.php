<?php

namespace common\modules\task\controllers;


use Yii;


class MailController extends \UrbanIndo\Yii2\Queue\Worker\Controller
{


    public function actionSend($to, $from = null, $subject, $body, $article_id = null)
    {
        if (!$from) {
            $from = $this->getDefaultFrom();
        }
        
        try {

            if (!is_null($article_id)) {
                $article = Yii::$container->get('common\modules\article\contracts\ArticleInterface');
                $active = $article->find()->select(['id'])->where(['id' => $article_id, 'enabled' => 1])->one();

                if (!is_object($active)) {
                    return false;
                }
            }

            $result = Yii::$app->mailer->compose()
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();

            if ($result) {
                Yii::error('email in queue not send ' . $to);
            }
        } catch (\Exception $ex) {
            Yii::error('email in queue not send ' . $to);
            return false;
        }
    }


    protected function getDefaultFrom()
    {
        return [Yii::$app->params['fromAddress'] => Yii::$app->params['fromName']];
    }
}
