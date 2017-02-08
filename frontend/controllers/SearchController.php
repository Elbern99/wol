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
    
    protected function getFilterData() {

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
        
        Yii::$app->getSession()->set('search_filters_data', $filters);
        
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
        
        if (Yii::$app->request->isPost) {
            
            if (Yii::$app->request->post('filter_content_type')) {
                $model->types = Yii::$app->request->post('filter_content_type');
            } else {
                $model->types = $model->getTypeIds();
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                if ($phrase != $model->search_phrase) {

                    try {
                        $searchResult = $model->search();
                    } catch (\Exception $e) {
                        Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Have problems in search request'));
                        $searchResult = [];
                    }

                    $phrase = $model->search_phrase;
                    Yii::$app->getSession()->set('search', serialize($searchResult));                  
                } else {

                    $searchResult = unserialize(Yii::$app->getSession()->get('search'));
                    Result::setFilter('types', Yii::$app->request->post('filter_content_type'));
                    Result::setFilter('subject', Yii::$app->request->post('filter_subject_type'));
                    Result::setFilter('biography', Yii::$app->request->post('filter_biography_type'));
                    Result::setFilter('topics', Yii::$app->request->post('filter_topics_type'));
                }

                Yii::$app->getSession()->set('search_criteria', serialize([
                    'search_phrase' => $phrase,
                    'types' => Yii::$app->request->post('filter_content_type')
                ]));
            }
   
        } else {
            
            if (Yii::$app->getSession()->has('search_filters_data')) {
                
                $sessionFilter = Yii::$app->getSession()->get('search_filters_data');
                if ($sessionFilter['types']['filtered']) {
                    Result::setFilter('types', $sessionFilter['types']['filtered']);
                }
                if ($sessionFilter['category']['filtered']) {
                    Result::setFilter('subject', $sessionFilter['category']['filtered']);
                }
                if ($sessionFilter['biography']['filtered']) {
                    Result::setFilter('biography', $sessionFilter['biography']['filtered']);
                }
                if ($sessionFilter['topics']['filtered']) {
                    Result::setFilter('topics', $sessionFilter['topics']['filtered']);
                }
            }
            
            $searchResult = unserialize(Yii::$app->getSession()->get('search'));
            $model->search_phrase = $phrase;      
        }
        
        $results = is_array($searchResult) ? $searchResult : [];
        
        unset($searchResult);
        $resultCount = count($results);
        
        if ($resultCount) {
            Result::initData($results);
        }
        
        $paginate = new Pagination(['totalCount' => count(Result::getSearchValue())]);
        $paginate->defaultPageSize = Yii::$app->request->get('count') ?? Yii::$app->params['search_result_limit'];
        
        $resultData = array_slice(Result::getSearchValue(), $paginate->offset, $paginate->limit);
        
        if (Yii::$app->request->isPost) {
            $filtersData = $this->getFilterData();
        } else {
            $filtersData = Yii::$app->getSession()->get('search_filters_data') ??  $this->getFilterData();
        }

        return $this->render('result', [
            'phrase' => $phrase,
            'search' => $model,
            'paginate' => $paginate, 
            'resultData' => $resultData,
            'topData' => Result::getSearchTopValue(),
            'resultCount' => $resultCount, 
            'filters' => $filtersData,
            'currentCountResult' => count(Result::getSearchValue())
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
            
            $savedSearchModel = SavedSearch::find()->where(['user_id' => Yii::$app->user->identity->id, 'id'=>$id])->one();
            
            if (is_object($savedSearchModel)) {
                $model->load($savedSearchModel->getAttributes(), '');
            }
            
        }
        
        if (Yii::$app->request->isGet) {
            if (Yii::$app->request->get('phrase')) {
                $model->search_phrase = Yii::$app->request->get('phrase');
            }
        }
        
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            
            try {
                
                $result = $model->search();
                Yii::$app->getSession()->set('search', serialize($result));
                Yii::$app->getSession()->set('search_criteria', serialize(Yii::$app->request->post('AdvancedSearchForm')));
                Yii::$app->getSession()->remove('search_filters_data');
                
                return $this->redirect(Url::to(['/search', 'phrase' => $model->search_phrase]));
            
            } catch (\Exception $e) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Error in Result'));
            }
            
        }
        
        return $this->render('advanced', ['search' => $model]);
    }
    
    public function actionRefine() {
        
        Yii::$app->getSession()->remove('search');
        Yii::$app->getSession()->remove('search_filters_data');
        
        return $this->redirect(Url::to(['/search']));
    }
    
    public function actionSave() {

        if (!Yii::$app->getSession()->get('search_criteria')) {
            throw new BadRequestHttpException();
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest || !is_object(Yii::$app->user->identity)) {
            return ['message' => 'You have not logged'];
        }
        
        $searchCriteria = unserialize(Yii::$app->getSession()->get('search_criteria'));
        
        if (!isset($searchCriteria['types'])) {
            return ['message' => 'Criteria not saved'];
        }
        
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
