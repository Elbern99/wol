<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CmsPages;
use common\models\CmsPageInfo;
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
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
        $pageInfoTable = CmsPageInfo::tableName();
        $page = CmsPages::find()
                ->alias('page')
                ->with([
                    'cmsPageSections' => function (\yii\db\ActiveQuery $query) {
                        $query->orderBy(['order' => SORT_ASC]);
                    }
                ])
                ->innerJoin(['info'=>$pageInfoTable], 'page.id = info.page_id')
                ->select([
                    'page.url', 'page.id', 'page.created_at', 
                    'info.title', 'info.meta_title', 'info.meta_keywords', 
                    'info.meta_description'
                ])
                ->where(['page.id'=>$id, 'page.enabled'=>1])
                ->asArray()
                ->one();

        if (!is_array($page) && count($page)) {
            throw new NotFoundHttpException('Page Not Found.');
        }
        
        return $this->render('index', ['page' => $page]);
    }

}