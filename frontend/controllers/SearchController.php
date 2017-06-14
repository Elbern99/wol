<?php
namespace frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use frontend\models\AdvancedSearchForm;
use yii\data\Pagination;
use frontend\models\Result;
use yii\helpers\Url;
use frontend\models\SavedSearch;
use common\models\SearchResult;
use frontend\components\search\ResultStrategy;
/**
 * Search controller
 */
class SearchController extends Controller
{
    use \frontend\components\search\traits\SearchTrait;
    
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
    public function actionIndex($search_phrase = null) {

        $model = new AdvancedSearchForm();
        $searchFiltersData = null;
        $searchResult = null;
        
        $searchResultId = Yii::$app->getSession()->get('search_result_id');
        $searchResultData = SearchResult::findOne($searchResultId);

        if (!$searchResultData) {
            $searchResultData = new SearchResult();
        }
        
        if (Yii::$app->request->isPost) {

            if (Yii::$app->request->post('result_page')) {
                $model->types = Yii::$app->request->post('filter_content_type');
            } else {
                $model->types = $model->getTypeIds();
            }

            if ($model->load(Yii::$app->request->post())) {
                
                Result::setSearchParams($model->getAttributes());
                
                if (($search_phrase != $model->search_phrase) && $model->validate()) {
                    return $this->postResearchData($model, $searchResultId, $searchResultData);
                }
                
                return $this->postFilteredData($model, $searchResultData);
            }
            
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text',"Your search parameters are incorrect,<br>Content types/Search phrase can not be empty"));
            return $this->redirect(['/search', 'search_phrase' => $search_phrase]);
        }
        
        $searchCreteria = unserialize($searchResultData->creteria);
        $model->load($searchCreteria, '');
        $previosRequestResult = (is_null(Yii::$app->getSession()->getFlash('previos_result')));
        
        if ($previosRequestResult && (!$searchResultData->id || ($searchCreteria['search_phrase'] != $search_phrase))) {

            if ($model->load(Yii::$app->request->get(), '')) {
                
                if (is_null($model->types)) {
                    $model->types = $model->getTypeIds();
                }
            
                $search_phrase = $model->search_phrase;
                
                try {
                    
                    if ($model->validate()) {

                        Result::setSearchParams($model->getAttributes());
                        $result = $this->getResearchData($model, $searchResultId, $searchResultData);

                        if ($result instanceof yii\web\Response) {
                            return $result;
                        }
                        
                        if (!$result) {
                            throw new \Exception();
                        }

                        $searchResultData = $result;
                        unset($result);
                    }
                    
                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash('error', Yii::t('app/text', "Search by those criteria return empty result"));
                    return $this->redirect(Url::to(['/search/advanced']));
                }
            }
        }

        $searchResult = unserialize($searchResultData->result);
        $searchFiltersData = $searchResultData->filters;

        if ($searchFiltersData) {
            $searchFiltersData = unserialize($searchFiltersData);
        } else {
            $searchFiltersData = $this->getFilterData($model, $searchResult['format'] ?? []);
        }

        $mainresultCount = count($searchResult['main']);
        $resultOrdered = [];

        if ($mainresultCount) {
            $filter = new \frontend\components\search\filters\MainSearchFilters($searchFiltersData, $searchResult['main']);
            $resultOrdered = $filter->getData();

            if (count($resultOrdered)) {
                $sortingResult = new ResultStrategy($resultOrdered);
                $sortingResult->setComparator($this->getOrderComparator($searchResult['origin']));
                $resultOrdered = $sortingResult->sort();
            }
        }
        
        $mainresultCount = count($resultOrdered);

        $paginate = new Pagination(['totalCount' => count($resultOrdered)]);
        $paginate->defaultPageSize = Yii::$app->params['search_result_limit'];
        $paginate->setPageSize(Yii::$app->request->get('count'));

        $resultData = array_slice($resultOrdered, $paginate->offset, $paginate->limit);
        $filter = new \frontend\components\search\filters\TopSearchFilters($searchFiltersData, $searchResult['top']);
        $topicsCount = 0;
        
        foreach ($filter->getData() as $top) {
            if ($top['type'] == 'key_topics') {
                $topicsCount += 1;
            }
        }

        return $this->render('result', [
            'phrase' => $search_phrase,
            'search' => $model,
            'paginate' => $paginate,
            'resultData' => $resultData,
            'topData' => $filter->getData(),
            'resultCount' => $mainresultCount + $topicsCount,
            'filters' => $searchFiltersData,
            'synonyms' => unserialize($searchResultData->synonyms),
        ]);
    }
    
    public function actionAjax()
    {
        try {
            
            $model = new AdvancedSearchForm();
            $model->types = $model->getTypeIds();
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
               
                $matches = $model->searchAjax();

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
    
    public function actionAdvanced($id = null) {
        
        $model = new AdvancedSearchForm();
        $model->setSelectedTypes();
        
        if ($id && is_object(Yii::$app->user->identity)) {
            
            $savedSearchModel = SavedSearch::find()->where(['user_id' => Yii::$app->user->identity->id, 'id' => $id])->one();

            if (is_object($savedSearchModel)) {
                $model->load($savedSearchModel->getAttributes(), '');
                $model->types = explode(',', $savedSearchModel->types);
            }
            
        }
        
        if (Yii::$app->request->isGet) {
            
            if (Yii::$app->request->get('refine')) {
                $searchResultId = Yii::$app->getSession()->get('search_result_id');
                if ($searchResultId) {
                    
                    $searchResultData = SearchResult::findOne($searchResultId);
                    
                    if (isset($searchResultData['creteria'])) {
                        $data = unserialize($searchResultData['creteria']);
                        $model->load($data, '');
                    }
                }

            } elseif (Yii::$app->request->get('phrase')) {
                $model->search_phrase = Yii::$app->request->get('phrase');
            }
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            
            try {
                
                $result = $model->search();
                
                if (count($result)) {
                    
                    $previosResult = Yii::$app->getSession()->get('search_result_id');
                    $searchResultData = SearchResult::findOne($previosResult);

                    if ($searchResultData) {
                        $searchResultData->delete();
                        Yii::$app->getSession()->remove('search_result_id');
                    }
                    
                    Result::setSearchParams($model->getAttributes());
                    
                    return $this->postSearchData($model, $result);
                }

                Yii::$app->getSession()->setFlash('error', Yii::t('app/text','According to the entered criteria are no results'));
                
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Error in Result'));
            }
            
        }
        
        return $this->render('advanced', ['search' => $model]);
    }
    
    public function actionRefine() {
        return $this->redirect(Url::to(['/search/advanced', 'refine' => true]));
    }
    
    public function actionSave() {

        $searchResultId = Yii::$app->getSession()->get('search_result_id');
        $searchResultData = SearchResult::find()->where(['id' => $searchResultId])->select('creteria')->one();
        
        if (!is_object($searchResultData)) {
            throw new BadRequestHttpException();
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest || !is_object(Yii::$app->user->identity)) {
            return ['message' => 'Please <a href="" class="fav-login">login</a> or <a href="/register" class="fav-register">register</a>'];
        }
        
        $searchCriteria = unserialize($searchResultData->creteria);

        try {
            
            $searchCriteria['types'] = implode(',', $searchCriteria['types']);
            $searchCriteria['user_id'] = Yii::$app->user->identity->id;
            $model = new SavedSearch();

            if ($model->load($searchCriteria, '') && $model->save()) {
                return ['message' => 'Search Saved To Your Account'];
            }
            
        } catch (\Exception $e) {
        } catch (\yii\db\Exception $e) {
        }
        
        return ['message' => 'Search Saved To Your Account'];
    }
    
}
