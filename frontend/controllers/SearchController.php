<?php
namespace frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use frontend\models\AdvancedSearchForm;
use frontend\models\SearchForm;
use yii\data\Pagination;
use frontend\models\Result;
use yii\helpers\Url;

/**
 * Search controller
 */
class SearchController extends Controller
{

    use \frontend\components\articles\SubjectTrait;
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
    public function actionIndex($phrase = null) {
        
        $model = new SearchForm();
        Result::setModel($model);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($phrase != $model->search_phrase) {
                
                try {
                    $searchResult = $model->search();
                } catch(\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Have problems in search request'));
                    $searchResult = [];
                }

                $phrase = $model->search_phrase;
                Yii::$app->getSession()->set('search', serialize($searchResult));
                
            } else {
                $searchResult = unserialize(Yii::$app->getSession()->get('search'));
                Result::setFilter('types',Yii::$app->request->post('filter_content_type'));
                Result::setFilter('subject',Yii::$app->request->post('filter_subject_type'));
            }
            
        } else {
            $searchResult = unserialize(Yii::$app->getSession()->get('search'));
            $model->search_phrase = $phrase;
        }

        $results = is_array($searchResult) ? $searchResult : [];
        $resultData = [];
        $resultCount = count($results);
        unset($searchResult);

        $paginate = new Pagination(['totalCount' => $resultCount]);
        $paginate->defaultPageSize = Yii::$app->request->get('count') ?? Yii::$app->params['search_result_limit'];

        if ($resultCount) {
            $resultData = array_slice($results, $paginate->offset, $paginate->limit);
            Result::initData($resultData);
        }
        
        return $this->render('result', [
            'phrase' => $phrase,
            'search' => $model,
            'paginate' => $paginate, 
            'resultData' => $resultData, 
            'resultCount' => $resultCount, 
            'subjectArea' => $this->getSubjectAreas()
        ]);
    }
    
    public function actionAjax()
    {
        try {
            $model = new SearchForm();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $matches = $model->search(['title']);

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
        
        $model = new AdvancedSearchForm();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            
            try {
                
                $result = $model->search();
                Yii::$app->getSession()->set('search', serialize($result));
                
                return $this->redirect(Url::to(['/search', 'phrase' => $model->search_phrase]));
            
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Error in Result'));
            }
            
        }
        
        return $this->render('advanced', ['search' => $model]);
    }
    
    public function actionRefine() {
        
        Yii::$app->getSession()->remove('search');
        
        return $this->redirect(Url::to(['/search']));
    }
    
}
