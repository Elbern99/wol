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
use yii\helpers\ArrayHelper;
use common\models\Article;
use common\modules\eav\CategoryCollection;
use common\models\Category;
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
        
        $articlesCollection = [];
        
        $favorit = FavoritArticle::find()
                    ->with(['article' => function($query){
                        return $query->alias('a')
                                ->with(['articleCategories' => function($query) {
                                    return $query->alias('ac')->select(['ac.article_id','ac.category_id', 'c.title', 'c.url_key'])
                                           ->innerJoin(Category::tableName().' AS c', 'c.id = ac.category_id')->asArray();
                                }])
                                ->select(['a.id', 'a.title', 'a.seo', 'a.availability', 'a.created_at'])
                                ->where(['a.enabled' => 1]);
                    }])
                    ->where(['user_id' => Yii::$app->user->identity->id])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->all();

        $articlesIds = ArrayHelper::getColumn($favorit, 'article_id');
        
        if (count($articlesIds)) {
            
            $categoryCollection = Yii::createObject(CategoryCollection::class);
            $categoryCollection->setAttributeFilter(['teaser', 'abstract']);
            $categoryCollection->initCollection(Article::tableName(), $articlesIds);
            $values = $categoryCollection->getValues();


            foreach ($favorit as $relation) {
                
                if (!$relation->article) {
                    continue;
                }
                
                $article = $relation->article;
                $articleCategory = [];

                foreach ($article->articleCategories as $c) {
                    $articleCategory[] = '<a href="' . $c['url_key'] . '" >' . $c['title'] . '</a>';
                }

                $articlesCollection[$article->id] = [
                    'title' => $article->title,
                    'url' => '/articles/' . $article->seo,
                    'availability' => $article->availability,
                    'teaser' => unserialize($values[$article->id]['teaser']),
                    'abstract' => unserialize($values[$article->id]['abstract']),
                    'created_at' => $article->created_at,
                    'category' => $articleCategory,
                    'fovorit_id' => $relation->id
                ];
            }
        }
        

        return [
            'articlesCollection' => $articlesCollection
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
