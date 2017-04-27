<?php
namespace frontend\components\search\traits;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\Result;
use yii\helpers\Url;
use common\models\SearchResult;
use frontend\components\search\ResultStrategy;
use frontend\models\AdvancedSearchForm;

trait SearchTrait {
    
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
    
    protected function postResearchData($model, $searchResultId, $searchResultData) {
        
        try {
            
            $searchResult = $model->search();

            if (!count($searchResult)) {

                if ($searchResultId) {
                    $searchResultData->delete();
                    Yii::$app->getSession()->remove('search_result_id');
                }

                return $this->redirect(array_merge(['/search'], $model->getAttributes()));
            }

        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', "Have problems in search request"));
            return $this->redirect(array_merge(['/search'], $model->getAttributes()));
        }

        $synonyms = $model->synonyms;
        $creteria = $model->getAttributes();
        
        Result::initData($searchResult);
        $filterData = $this->getFilterData($model);

        $searchResultArgs = [
            'result' => $searchResult,
            'creteria' => $creteria,
            'filters' => $filterData,
            'synonyms' => $synonyms
        ];

        SearchResult::refreshResult($searchResultData, $searchResultArgs);
        return $this->redirect(array_merge(['/search'], $creteria));
    }
    
    protected function postFilteredData($model, $searchResultData) {
        
        $searchResult = unserialize($searchResultData->result);
        $synonyms = null;
        $creteria = $searchResultData->mixSearchCreteriaArray($model->getAttributes());

        Result::setFilter('types', Yii::$app->request->post('filter_content_type'));
        Result::setFilter('subject', Yii::$app->request->post('filter_subject_type'));
        Result::setFilter('biography', Yii::$app->request->post('filter_biography_type'));
        Result::setFilter('topics', Yii::$app->request->post('filter_topics_type'));
        
        $filterData = $this->getFilterData($model);

        $searchResultArgs = [
            'result' => $searchResult,
            'creteria' => $creteria,
            'filters' => $filterData,
            'synonyms' => $synonyms
        ];

        SearchResult::refreshResult($searchResultData, $searchResultArgs);
        return $this->redirect(array_merge(['/search'], $creteria));
        
    }
    
    protected function getResearchData($model, $searchResultId, $searchResultData) {
        
        $searchResult = $model->search();
        $synonyms = $model->synonyms;
        $creteria = $model->getAttributes();
        Result::initData($searchResult);
        $filterData = $this->getFilterData($model);

        $searchResultArgs = [
            'result' => $searchResult,
            'creteria' => $creteria,
            'filters' => $filterData,
            'synonyms' => $synonyms
        ];

        return SearchResult::refreshResult($searchResultData, $searchResultArgs);
    }
}