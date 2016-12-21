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

    public function actionAdvancedSearch() {
        return $this->render('advanced-search');
    }

    public function actionSubscribeToNewsletter() {
        return $this->render('subscribe-to-newsletter');
    }

    public function actionSearchResults() {
        return $this->render('search-results');
    }

    public function actionRegister() {
        return $this->render('register');
    }

    public function actionMyAccount() {
        return $this->render('my-account');
    }

    public function actionUi() {
        return $this->render('ui');
    }
}
