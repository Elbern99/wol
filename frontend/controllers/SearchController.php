<?php
namespace frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\ArticleSearch;
use yii\sphinx\MatchExpression;
use yii\helpers\ArrayHelper;
use frontend\models\SearchForm;
use yii\data\Pagination;
use frontend\models\Result;

/**
 * Search controller
 */
class SearchController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'ajax' => ['post'],
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
            ]
        ];
    }

    /**
     * Displays Page.
     *
     * @return mixed
     */
    public function actionIndex() {
        
        $searchResult = Yii::$app->getSession()->get('search');
        $results = $searchResult ? unserialize($searchResult) : [];
        $resultData = [];
        $resultCount = count($results);
        unset($searchResult);

        $paginate = new Pagination(['totalCount' => $resultCount]);
        
        if (true) {
            $paginate->defaultPageSize = 2;
        }
        
        if ($resultCount) {
            $resultData = array_slice($results, $paginate->offset, $paginate->limit);
            Result::initData($resultData);
        }
        
        return $this->render('result', ['paginate' => $paginate, 'resultData' => $resultData, 'resultCount'=>$resultCount]);
        die;
    }
    
    public function actionAjax()
    {
        try {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $searchPhrase = Yii::$app->request->post('search');

            if (strlen(str_replace(' ','',$searchPhrase)) >= 3) {

                $matches = ArticleSearch::find()->select(['id', 'title'])->match($searchPhrase)->asArray()->all();

                if (count($matches)) {
                    $columns = ArrayHelper::getColumn($matches, 'title');
                    return $columns;
                }
            }

            return [];
            
        } catch (\Exception $e) {
            throw new BadRequestHttpException();
        }
    }
    
    public function actionAdvanced() {
        
        $model = new SearchForm();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            
            try {
                
                $result = $model->search();

                Yii::$app->getSession()->set('search', serialize($result));
                return $this->redirect('/search');
            
            } catch (\Exception $e) { 
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Error in Result'));
            }
            
        }
        
        return $this->render('advanced', ['search' => $model]);
    }
    
}
