<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\SignupPopupForm;
use frontend\models\NewsletterForm;
use common\models\NewsletterNews;
use yii\helpers\Html;
use common\models\UserActivation;
use common\models\Newsletter;

/**
 * Site controller
 */
class SiteController extends Controller {

    use traits\HomeTrait;
    use traits\SourcesTrait;
    use traits\RedirectLoginUserTrait;
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'login'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'login' => ['post']
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {

        return $this->render('index', $this->getHomeParams());
    }
    
    public function actionSources() {
        
        return $this->render('source', $this->getSourcePageData());
    }
    
    public function actionSubscribe() {
        
        $model = new NewsletterForm();
        $newsletterArchive = NewsletterNews::find()->select(['title', 'date', 'url_key'])->orderBy(['date' => SORT_DESC])->all();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $newslatter = Yii::$container->get('newsletter');
            $newslatter->getSubscriber($model->email);

            if ($newslatter->setSubscriber($model->getAttributes())) {
                Yii::$app->getSession()->setFlash('success', 'You subscribed.');
            } else {
                Yii::$app->getSession()->setFlash('error', 'You not subscribed.');
            }
                
        }
        
        return $this->render('subscribe', ['model' => $model, 'newsletterArchive' => $newsletterArchive]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect($this->getLoginRedirect());
        }
        
        if ($model->getErrors()) {
            
            $text = '';
            
            foreach ($model->getErrors() as $error) {
                $text .= Html::tag('p', current($error));
            }
            
            Yii::$app->getSession()->setFlash('error', $text);
        } else {
            Yii::$app->getSession()->setFlash('error', 'Incorrect email or password.');
        }
        return $this->goBack();
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        
        $model = new SignupForm();
        $modelPopup = new SignupPopupForm();

        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post())) {
                
                if ($model->signup()) {
                    
                    if ($model->errorMessage === false) {
                        Yii::$app->session->setFlash('success', 'You have been successfully registered. Please confirm your email!');
                    } else {
                        Yii::$app->session->setFlash('error', implode("<br>", $model->errorMessage));
                    }
                    
                    return $this->goHome();
                }
                
            } elseif ($modelPopup->load(Yii::$app->request->post())) {
                
                if ($modelPopup->signup()) {
                    Yii::$app->session->setFlash('success', 'You have been successfully registered');
                    return $this->goHome();
                }
            }
           
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        
        $model = new PasswordResetRequestForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
                
            } else {
                
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            
            $model = new ResetPasswordForm($token);
            
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    
    public function actionConfirm($token) {
        
        $model = UserActivation::find()->where(['token' => $token])->one();

        if (!$model) {
            return $this->goHome();
        }

        if($user = $model->verifyToken($token)) {

            $register = new SignupForm();
            $register->email = $user->email;
            $obj = Yii::$container->get('newsletter');
            $obj->getSubscriber($user->email);
            $subscriberId = $obj->getAttribute('code');
            $register->sendRegisteredEmail($subscriberId, $user);
            
            Yii::$app->user->login($user);
            
            return $this->redirect($this->getLoginRedirect());
        }

        return $this->goHome();
    }
    
    public function actionUnsubscribe($number) {
        
        try {
            
            $model = Newsletter::find()->where(['code' => $number])->one();
            
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','You successfully unsubscribed'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','You cannot unsubscribe now, try later!'));
        }
             
        return $this->goHome();
    }
    
    public function actionAddCloseCookie($name) {
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            if ($name) {

                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => $name,
                    'expire' => time() + 86400 * 30,
                    'value' => true
                ]));
                
                return ['result' => true];
            }
        } catch (\yii\db\Exception $e) {
        }
        
        return ['result' => false];
    }

}
