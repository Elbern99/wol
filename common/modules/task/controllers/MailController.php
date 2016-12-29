<?php
namespace common\modules\task\controllers;
use Yii;

class MailController extends \UrbanIndo\Yii2\Queue\Worker\Controller {

    public function actionSend($to, $from, $subject, $body, $article_id = null) {
        
        try {
            
            if (!is_null($article_id)) {
                $article = Yii::$container->get('common\modules\article\contracts\ArticleInterface');
                $active = $article->find()->select(['id'])->where(['id' => $article_id, 'enabled' => 1])->one();
                
                if (!is_object($active)) {
                    return false;
                }
            }
            
            $send = Yii::$app->mailer->compose()
                        ->setFrom($to)
                        ->setTo(htmlspecialchars($from))
                        ->setSubject($subject)
                        ->setHtmlBody($body)
                        ->send();

            if (!$send) {
                \Yii::error($to.' message not sent');
                return false;
            }
            
        } catch (\Exception $ex){
            \Yii::error('email in queue not send '.$to);
            return false;
        }
        
    }
}