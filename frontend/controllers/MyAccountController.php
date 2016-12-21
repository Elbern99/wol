<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\UserProfileForm;
use common\models\FavoritArticle;
/**
 * Site controller
 */
class MyAccountController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'change-avatar', 'edit-user-data', 'delete', 'remove-favorite'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'change-avatar' => ['post'],
                    'edit-user-data' => ['post']
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
    
    protected function getTabs() {
        
        return [
            'account' => ['path'=>'tabs/account.php', 'params' => $this->getAccountParams()],
            'article' => ['path'=>'tabs/article.php', 'params' => $this->getFavoritArticle()],
            'search' => ['path'=>'tabs/search.php', 'params' => []]
        ];
    }
    
    protected function getAccountParams() {
        
        $form = new UserProfileForm();

        return [
            'model' => $form
        ];
    }
    
    protected function getFavoritArticle() {
        
        $articles = FavoritArticle::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
        
        return [
            'articles' => $articles  
        ];
    }

    public function actionIndex() {

        return $this->render('index', ['tabs' => $this->getTabs(), 'user' => Yii::$app->user->identity]);
    }
    
    public function actionChangeAvatar() {
        
        try {
            
            if (Yii::$app->request->getIsAjax()) {

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                $user = new UserProfileForm();
                $user->initUploadProperty();

                $result = $user->upload();

                if ($result && $user->saveAvatar()) {
                    return ['success' => $user->getAvatarUrl($user->avatar->name)];
                }

                return [];
            }

            return $this->goHome();
            
        } catch (\Exception $e) {
            throw new BadRequestHttpException();
        }
    }
    
    public function actionEditUserData() {
        
        if (Yii::$app->request->isPost) {
            
            $user = new UserProfileForm();

            if ($user->load(Yii::$app->request->post()) && $user->validate()) {

                if ($user->saveUserData()) {
                    
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Your data was change'), false);
                }
                
                return $this->redirect('/my-account');
            }
            
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Your data was not change'), false);
            return $this->redirect('/my-account');
        }
        
        return $this->goHome();
    }
    
    public function actionDelete() {
        
        try {
            
            $model = Yii::$app->user->identity;
            if (!is_object($model)) {
                throw new BadRequestHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->user->logout();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Account was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Account was not delete!'));
        }

        return $this->goHome();
    }
    
    public function actionRemoveFavorite($id) {
        
        try {
            
            $model = FavoritArticle::findOne($id);
            
            if (!is_object($model)) {
                throw new BadRequestHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Favorite Article deleted'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Favorite Article was not delete!'));
        }
             
        return $this->redirect('/my-account#tab-2');
    }

}
