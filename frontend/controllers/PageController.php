<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\CmsPagesRepository as Page;
/**
 * Cms Page controller
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'contact','faq', 'editorial-board', 'contributor-profile'],
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
    public function actions()
    {
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
     * Displays Page.
     *
     * @return mixed
     */
    public function actionIndex($id)
    {
        try {
            
            $page = Page::getPageById($id);
            return $this->render($page->Cms('key'), ['page' => $page]);
            
        } catch(\Exception $e) {
            throw new NotFoundHttpException('Page Not Found.');
        }
    }
	
	/*public function actionContact()
	{
		return $this->render('contact');
	}
	
	public function actionFaq()
	{
		return $this->render('faq');
	}
	
	public function actionEditorialBoard()
	{
		return $this->render('editorial-board');
	}
	
	public function actionContributorProfile()
	{
		return $this->render('contributor-profile');
	}*/
	
	
}
