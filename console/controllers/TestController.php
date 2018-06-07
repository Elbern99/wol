<?php

namespace console\controllers;


use yii\console\Controller;
use yii\helpers\Console;
use Yii;


class TestController extends Controller
{


    public function actionMail($email = null)
    {
        $email = $email ? $email : 'andriy.volberg@gmail.com';

        Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->params['fromAddress'] => Yii::$app->params['fromName']])
            ->setTo($email)
            ->setSubject('Test From Stagng')
            ->setTextBody('Plain text content')
            ->setHtmlBody('<b>HTML content</b>')
            ->send();
    }
}
