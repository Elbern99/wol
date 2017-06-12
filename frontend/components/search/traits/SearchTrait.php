<?php
namespace frontend\components\search\traits;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\Result;
use yii\helpers\Url;
use common\models\SearchResult;
use frontend\components\search\ResultStrategy;
use frontend\models\AdvancedSearchForm;
use frontend\models\SearchFilters;
use frontend\components\articles\OrderBehavior;
use common\models\ArticleCategory;

trait SearchTrait {
    
    use \frontend\components\articles\SubjectTrait;
    
    protected function getFilterData($model, $formatData) {
        
        $searchResultId = Yii::$app->getSession()->get('search_result_id');
        SearchFilters::initFilterData($formatData);
        
        $filters['types'] = [
            'data' => AdvancedSearchForm::class,
            'selected' => $formatData,
            'filtered' => SearchFilters::getFilter('types')
        ];
        
        $filters['category'] = [
            'data' => $this->getSubjectAreas(),
            'selected' => SearchFilters::$articleCategoryIds,
            'filtered' => SearchFilters::getFilter('subject')
        ];

        $filters['biography'] = [
            'data' => SearchFilters::$biographyFilter,
            'selected' => count(SearchFilters::getFilter('biography')) ? true : false,
            'filtered' => SearchFilters::getFilter('biography'),
        ];

        $filters['topics'] = [
            'data' => SearchFilters::$topicsFilter,
            'selected' => count(SearchFilters::getFilter('topics')) ? true : false,
            'filtered' => SearchFilters::getFilter('topics'),
        ];

        return $filters;
    }
    
    protected function clearSearchResultByUserIP() {
        
        $ip = Yii::$app->request->getUserIP();
        
        if ($ip) {
            SearchResult::deleteAll(['ip' => $ip]);
        }
    }
    
    protected function updateSearchData($model, $searchResultId, $searchResultData) {
        try {
            $this->clearSearchResultByUserIP();
            
            $searchResult = $model->search();

            if (!count($searchResult)) {

                if ($searchResultId) {
                    $searchResultData->delete();
                    Yii::$app->getSession()->remove('search_result_id');
                }
                Yii::$app->getSession()->setFlash('previos_result', 'empty');
                return $this->redirect(array_merge(['/search'], $model->getAttributes()));
            }

        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', "Have problems in search request"));
            return $this->redirect(array_merge(['/search'], $model->getAttributes()));
        }

        $synonyms = $model->synonyms;
        $creteria = $model->getAttributes();
        
        Result::$synonyms = $synonyms;
        Result::initData($searchResult);
        
        $result = [
            'top'  =>  Result::getSearchTopValue(),
            'main' =>  Result::getSearchValue(),
            'format' => Result::$formatData,
            'origin' => Result::getOriginData()
        ];

        SearchFilters::setFilter('types', $model->types);
        if (isset($result['format']['biography'])) {
            SearchFilters::setFilter('biography', $result['format']['biography']);
        }
        if (isset($result['format']['key_topics'])) {
            SearchFilters::setFilter('topics', $result['format']['key_topics']);
        }
        if (isset($result['format']['article'])) {
            SearchFilters::setFilter('subject', $this->getCategoriesInArticles($result['format']['article']));
        }

        $filterData = $this->getFilterData($model, Result::$formatData);
        
        $searchResultArgs = [
            'result' => $result,
            'creteria' => $creteria,
            'filters' => $filterData,
            'synonyms' => $synonyms
        ];

        return SearchResult::refreshResult($searchResultData, $searchResultArgs);
    }
    
    protected function postResearchData($model, $searchResultId, $searchResultData) {
        
        $this->updateSearchData($model, $searchResultId, $searchResultData);
        return $this->redirect(array_merge(['/search'], $model->getAttributes()));
    }
    
    protected function postFilteredData($model, $searchResultData) {
        
        $searchResult = unserialize($searchResultData->result);
        $synonyms = false;
        $creteria = $searchResultData->mixSearchCreteriaArray($model->getAttributes());

        SearchFilters::setFilter('types', Yii::$app->request->post('filter_content_type'));
        SearchFilters::setFilter('subject', Yii::$app->request->post('filter_subject_type'));
        SearchFilters::setFilter('biography', Yii::$app->request->post('filter_biography_type'));
        SearchFilters::setFilter('topics', Yii::$app->request->post('filter_topics_type'));
        
        $filterData = $this->getFilterData($model, $searchResult['format'] ?? []);

        $searchResultArgs = [
            'result' => $searchResult,
            'creteria' => $creteria,
            'filters' => $filterData,
            'synonyms' => $synonyms
        ];

        SearchResult::refreshResult($searchResultData, $searchResultArgs);
        return $this->redirect(array_merge(['/search'], $creteria));
        
    }
    
    protected function getCategoriesInArticles($articleIds) {
        
        $categories = ArticleCategory::find()
                            ->select(['category_id'])
                            ->where(['article_id' => $articleIds])
                            ->groupBy('category_id')
                            ->asArray()
                            ->all();
        
        return ArrayHelper::getColumn($categories, 'category_id');
    }
    
    protected function postSearchData($model, $searchResult) {

        $synonyms = $model->synonyms;
        $creteria = $model->getAttributes();
        
        Result::$synonyms = $synonyms;
        Result::initData($searchResult);
   
        $result = [
            'top'  =>  Result::getSearchTopValue(),
            'main' =>  Result::getSearchValue(),
            'format' => Result::$formatData,
            'origin' => Result::getOriginData()
        ];

        SearchFilters::setFilter('types', $model->types);
        if (isset($result['format']['biography'])) {
            SearchFilters::setFilter('biography', $result['format']['biography']);
        }
        if (isset($result['format']['key_topics'])) {
            SearchFilters::setFilter('topics', $result['format']['key_topics']);
        }
        if (isset($result['format']['article'])) {
            SearchFilters::setFilter('subject', $this->getCategoriesInArticles($result['format']['article']));
        }

        $filterData = $this->getFilterData($model, Result::$formatData);
        
        $searchResultArgs = [
            'result' => $result,
            'creteria' => $creteria,
            'filters' => $filterData,
            'synonyms' => $synonyms
        ];

        $currentResult = SearchResult::addNewResult($searchResultArgs);

        if ($currentResult) {
            return $this->redirect(Url::to(array_merge(['/search'], Yii::$app->request->post('AdvancedSearchForm'))));
        }
        
        return $this->redirect(Url::to(['/search/advanced']));
    }
    
    protected function getResearchData($model, $searchResultId, $searchResultData) {
        
        return $this->updateSearchData($model, $searchResultId, $searchResultData);
    }
    
    protected function getOrderComparator($results) {
        
        $order = (is_null(Yii::$app->request->get('sort'))) ? 'relevance' : Yii::$app->request->get('sort');

        switch($order) {
            case 'relevance':
                return new \frontend\components\search\comparators\RelevantComparator($results);
            case OrderBehavior::DATE_DESC:
                return new \frontend\components\search\comparators\DateComparator('desc');
            case OrderBehavior::DATE_ASC:
                return new \frontend\components\search\comparators\DateComparator('asc');
            case OrderBehavior::AUTHOR_ASC:
                return new \frontend\components\search\comparators\AuthorComparator('asc');
            case OrderBehavior::AUTHOR_DESC:
                return new \frontend\components\search\comparators\AuthorComparator('desc');
            case OrderBehavior::TITLE_ASC:
                return new \frontend\components\search\comparators\TitleComparator('asc');
            case OrderBehavior::TITLE_DESC:
                return new \frontend\components\search\comparators\TitleComparator('desc');
            default :
                return new \frontend\components\search\comparators\RelevantComparator($results);
        }
    }
}