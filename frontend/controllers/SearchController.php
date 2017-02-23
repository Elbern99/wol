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
use frontend\components\articles\OrderBehavior;
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
    
    protected function getFilterData($model) {
        
        $searchResultId = Yii::$app->getSession()->get('search_result_id');
        
        $filters['types'] = [
            'data' => AdvancedSearchForm::class,
            'selected' => Result::$formatData,
            'filtered' => Result::getFilter('types')
        ];
        
        $filters['category'] = [
            'data' => $this->getSubjectAreas(),
            'selected' => Result::$articleCategoryIds,
            'filtered' => Result::getFilter('subject')
        ];

        $filters['biography'] = [
            'data' => Result::$biographyFilter,
            'selected' => count(Result::getFilter('biography')) ? true : false,
            'filtered' => Result::getFilter('biography'),
        ];

        $filters['topics'] = [
            'data' => Result::$topicsFilter,
            'selected' => count(Result::getFilter('topics')) ? true : false,
            'filtered' => Result::getFilter('topics'),
        ];

        return $filters;
    }

    /**
     * Displays Page.
     *
     * @return mixed
     */
    public function actionIndex($phrase = null) {

        $model = new AdvancedSearchForm();
        Result::setModel($model);
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

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                if ($phrase != $model->search_phrase) {

                    try {
                        $searchResult = $model->search();
                    } catch (\Exception $e) {
                        Yii::$app->getSession()->setFlash('error', Yii::t('app/text', "Have problems in search request"));
                    }

                    $phrase = $model->search_phrase; 

                } else {
                    $searchResult = unserialize($searchResultData->result);
                    Result::setFilter('types', Yii::$app->request->post('filter_content_type'));
                    Result::setFilter('subject', Yii::$app->request->post('filter_subject_type'));
                    Result::setFilter('biography', Yii::$app->request->post('filter_biography_type'));
                    Result::setFilter('topics', Yii::$app->request->post('filter_topics_type'));
                }
                
                Result::initData($searchResult);
                $filterData = $this->getFilterData($model);
                
                SearchResult::refreshResult($searchResultId, $searchResult, $model->getAttributes(), $filterData);

            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text',"Your search parameters are incorrect,<br>Content types/Search phrase can not be empty"));
            }
            
            return $this->redirect(['/search', 'phrase' => $phrase]);
        }

        $searchFiltersData = $searchResultData->filters;

        if ($searchFiltersData) {

            $searchFiltersData = unserialize($searchFiltersData);
            if ($searchFiltersData['types']['filtered']) {
                Result::setFilter('types', $searchFiltersData['types']['filtered']);
            }
            Result::setFilter('subject', $searchFiltersData['category']['filtered']);
            Result::setFilter('biography', $searchFiltersData['biography']['filtered']);
            Result::setFilter('topics', $searchFiltersData['topics']['filtered']);
        }

        $searchResult = unserialize($searchResultData->result);
        $searchCreteria = unserialize($searchResultData->creteria);
        $model->load($searchCreteria, '');      
        
        $results = is_array($searchResult) ? $searchResult : [];
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');

        switch($order) {
            case 'relevance':
                $comparator = new \frontend\components\search\comparators\RelevantComparator();
                break;
            case OrderBehavior::DATE_DESC:
                $comparator = new \frontend\components\search\comparators\DateComparator('desc');
                break;
            case OrderBehavior::DATE_ASC:
                $comparator = new \frontend\components\search\comparators\DateComparator('asc');
                break;
            case OrderBehavior::AUTHOR_ASC:
                $comparator = new \frontend\components\search\comparators\AuthorComparator('asc');
                break;
            case OrderBehavior::AUTHOR_DESC:
                $comparator = new \frontend\components\search\comparators\AuthorComparator('desc');
                break;
            case OrderBehavior::TITLE_ASC:
                $comparator = new \frontend\components\search\comparators\TitleComparator('asc');
                break;
            case OrderBehavior::TITLE_DESC:
                $comparator = new \frontend\components\search\comparators\TitleComparator('desc');
                break;
            default :
                $comparator = new \frontend\components\search\comparators\RelevantComparator();
        }

        unset($searchResult);
        $resultCount = count($results);
        
        if ($resultCount) {
            Result::initData($results);
        }

        $sortingResult = new ResultStrategy(Result::getSearchValue());
        $sortingResult->setComparator($comparator);
        $resultOrdered = $sortingResult->sort();
        
        $paginate = new Pagination(['totalCount' => count($resultOrdered), 'params' =>  array_merge(Yii::$app->request->get(), ['phrase' => $phrase])]);
        $paginate->defaultPageSize = Yii::$app->request->get('count') ?? Yii::$app->params['search_result_limit'];
        
        $resultData = array_slice($resultOrdered, $paginate->offset, $paginate->limit);
        $searchFiltersData = $searchFiltersData ??  $this->getFilterData($model);

        return $this->render('result', [
            'phrase' => $phrase,
            'search' => $model,
            'paginate' => $paginate, 
            'resultData' => $resultData,
            'topData' => Result::getSearchTopValue(),
            'resultCount' => $resultCount, 
            'filters' => $searchFiltersData,
            'currentCountResult' => count($resultOrdered)
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

                    $currentResult = SearchResult::addNewResult($result, Yii::$app->request->post('AdvancedSearchForm'));

                    if ($currentResult) {
                        return $this->redirect(Url::to(['/search', 'phrase' => $model->search_phrase]));
                    }
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
                return ['message' => 'Criteria saved'];
            }
            
        } catch (\Exception $e) {
        } catch (\yii\db\Exception $e) {
        }
        
        return ['message' => 'Criteria not saved'];
    }
    
}
