<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\AuthorRoles;
use common\modules\author\Roles;
use yii\helpers\ArrayHelper;
use common\models\Author;
use common\modules\eav\CategoryCollection;
use common\modules\eav\helper\EavValueHelper;
use common\models\ExpertSearch;
use yii\data\Pagination;

class AuthorsController extends Controller {
    
    use traits\ExpertTrait;
    
    public function actionIndex() {
        
        $collection = [];
        
        $query = Author::find()
                           ->select(['id'])
                           ->with(['articleAuthors.article' => function($query) {
                               return $query->select(['id', 'seo', 'title']);
                           }])
                           ->where(['enabled' => 1]);
                           
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = Yii::$app->params['authors_limit'];
        $authors = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
          
        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['name', 'affiliation']);
        $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($authors, 'id'));
        $authorValues = $authorCollection->getValues();
        
        foreach ($authors as $author) {
            
            $name = EavValueHelper::getValue($authorValues[$author['id']], 'name', function($data){ return $data; });
            $affiliation = EavValueHelper::getValue($authorValues[$author['id']], 'affiliation', function($data) { return $data->affiliation; }, 'string');
            $articles = [];
            
            if (!empty($author['articleAuthors'])) {
                $articles = ArrayHelper::getColumn($author['articleAuthors'], 'article');
            }
            
            $collection[$author['id']] = [
                'name' => $name,
                'affiliation' => $affiliation,
                'articles' => $articles
            ];
        }
        
        return $this->render('authors_list', ['collection' => $collection, 'paginate' => $pages]);
        
    }

    public function actionExpert() {
        
        $limit = $this->getLimit();
        $finds = new ExpertSearch();
        $roles = new Roles();
        
        $expertCollection = [];
        $expertRoleId = $roles->getTypeByLabel('expert');
        
        $filter = $this->getFilterData(Author::tableName(), $expertRoleId);
        $finds->setFilter($filter);
        
        $loadSearch = false;
        
        if (Yii::$app->request->isPost && $finds->load(Yii::$app->request->post())) {
            
            if ($finds->validate()) {

                $results = $this->getSearchResult($finds);

                if (count($results)) {
                    
                    $experstIds = ArrayHelper::getColumn($results, 'id');
                    $loadSearch = true;
                }
            }
        }
        
        if (!$loadSearch) {
            
            $experstIds = $this->getExpertIds($expertRoleId);
            $experstIds = ArrayHelper::getColumn($experstIds, 'author_id');
        }
        
        if (!count($experstIds)) {
            
            return $this->render('expert', [
                'expertCollection' => $expertCollection, 
                'limit' => $limit, 
                'expertCount' => count($experstIds), 
                'search' => $finds,
                'filter' => $filter
            ]);
        }
        
        
        $experts = Author::find()
                        ->select(['id', 'avatar', 'author_key'])
                        ->where(['enabled' => 1, 'id' => $experstIds]);
        
        if (!$loadSearch) {
            $experts->limit($limit);
        }
                        
        $experts = $experts->all();

        $authorCollection = Yii::createObject(CategoryCollection::class);
        $authorCollection->setAttributeFilter(['name', 'affiliation', 'experience_type', 'expertise', 'language', 'author_country']);
        $authorCollection->initCollection(Author::tableName(), ArrayHelper::getColumn($experts, function($model) { return $model->id;}));
        $authorValues = $authorCollection->getValues();
        
        foreach ($experts as $expert) {

            if (!isset($authorValues[$expert->id])) {
                continue;
            }

            $name = EavValueHelper::getValue($authorValues[$expert->id], 'name', function($data){ return $data; });
            $experience_type = EavValueHelper::getValue($authorValues[$expert->id], 'experience_type', function($data) { return $data->expertise_type; }, 'array');
            $affiliation = EavValueHelper::getValue($authorValues[$expert->id], 'affiliation', function($data) { return $data->affiliation; }, 'string');
            $expertise = EavValueHelper::getValue($authorValues[$expert->id], 'expertise', function($data) { return $data->expertise; }, 'array');
            $language = EavValueHelper::getValue($authorValues[$expert->id], 'language', function($data){ return $data->code; }, 'array');
            $author_country = EavValueHelper::getValue($authorValues[$expert->id], 'author_country', function($data){ return $data->code; }, 'array');
            
            $data = [
                'affiliation' => $affiliation,
                'avatar' => ($expert->avatar) ? Author::getImageUrl($expert->avatar) : null,
                'name' => $name,
                'experience_type' => $experience_type,
                'expertise' => $expertise,
                'language' => $language,
                'author_country' => $author_country
            ];
            
            if (Yii::$app->request->isPost) {
                
                if ($finds->filtered($data)) {
                    continue;
                }
            }
            
            $expertCollection[$expert->id] = $data;

        }

        return $this->render('expert', [
            'expertCollection' => $expertCollection, 
            'limit' => $limit, 
            'expertCount' => count($experstIds), 
            'search' => $finds,
            'filter' => $filter
        ]);
    }
}

