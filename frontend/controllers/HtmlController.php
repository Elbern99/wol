<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Cms Page controller
 */
class HtmlController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'contact', 'faq', 'editorial-board', 'contributor-profile', 'for-contributor', 'about', 'news-article', 'article','articles-list'],
                        'allow' => true,
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionContact() {
        return $this->render('contact');
    }

    public function actionFaq() {
        return $this->render('faq');
    }

    public function actionEditorialBoard() {
        return $this->render('editorial-board');
    }

    public function actionContributorProfile() {
        return $this->render('contributor-profile');
    }

    public function actionForContributor() {
        return $this->render('for-contributor');
    }

    public function actionAbout() {
        return $this->render('about');
    }

    public function actionNewsArticle() {
        return $this->render('news-article');
    }
								
				public function actionArticle() {
								return $this->render('article');
				}
								
				public function actionArticlesList() {
								return $this->render('articles-list');
				}

}
