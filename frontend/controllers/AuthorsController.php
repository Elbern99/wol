<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\AuthorRoles;
use common\modules\author\Roles;
use yii\helpers\ArrayHelper;
use common\models\Author;
use Yii;
use common\modules\eav\CategoryCollection;
use common\modules\eav\helper\EavValueHelper;
use common\models\ExpertSearch;

class AuthorsController extends Controller {
    
    protected function getLimit() {
        
        $limit = Yii::$app->params['expert_limit'];
        $limitPrev = Yii::$app->request->get('limit');
        
        if (Yii::$app->request->getIsPjax()) {
            
            if (intval($limitPrev)) {
                $limit += (int)$limitPrev;
            }
            
        } elseif (intval($limitPrev)) {
            $limit = $limitPrev;
        }
        
        return $limit;
    }
    
    protected function getFilterData($type, $role) {
        
        $cache = Yii::$app->cache;
        $key = 'FILTER_EXPERT_OPTIONS';
        
        $data = $cache->get($key);
        
        if ($data !== false) {
            return $data;
        }
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `ea`.`name` as `attr_name`, `ev`.`value` as `value`
            FROM `author` as `a`
            join `author_roles` as `ar` on `a`.`id` = `ar`.`author_id`
            join `eav_type` as `et`
            join `eav_attribute` as `ea` on `ea`.`name` IN('experience_type', 'expertise', 'language', 'author_country')
            left join `eav_entity` as `e` on `e`.`model_id` = `a`.`id` and `et`.`id` = `e`.`type_id`
            left join `eav_type_attribute` on `ea`.`id` = `eav_type_attribute`.`attribute_id` AND `eav_type_attribute`.`type_id` = `et`.`id`
            left join `eav_value` as `ev` on `ev`.`entity_id` = `e`.`id` and `ev`.`attribute_id` = `ea`.`id`
            WHERE `et`.`name` = :type AND `ar`.`role_id` = :role
            ORDER BY `a`.`id` ASC
        ", [':type' => $type, ':role' => $role]);
        
        $results = $command->queryAll();
        $filters = [];
        
        if (count($results)) {

            foreach ($results as $result) {
                
                $attrData = $this->getAttributeValue($result['attr_name'], $result['value']);
                
                if (empty($attrData)) {
                    continue;
                }
                
                if (isset($filters[$result['attr_name']])) {

                    $filters[$result['attr_name']]= array_unique(array_merge($attrData, $filters[$result['attr_name']]));
                    continue;
                }
                
                $filters[$result['attr_name']]= $attrData;
            }

        }
        
        $cache->set($key, $filters, 86400);
        return $filters;
    }
    
    protected function getAttributeValue($attr, $value) {
        
        switch ($attr) {
            case 'experience_type':
                return EavValueHelper::getValue(['experience_type'=>$value], 'experience_type', function($data) { return $data->expertise_type; }, 'array');
            case 'expertise':
                return EavValueHelper::getValue(['expertise'=>$value], 'expertise', function($data) { return $data->expertise; }, 'array');
            case 'language':
                return EavValueHelper::getValue(['language'=>$value], 'language', function($data){ return $data->code; }, 'array');
            case 'author_country':
                return EavValueHelper::getValue(['author_country'=>$value], 'author_country', function($data){ return $data->code; }, 'array');
        }
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

                $results = $finds->find()
                                 ->select(['id'])
                                 ->match($finds->search_phrase)
                                 ->asArray()
                                 ->all();

                if (count($results)) {
                    
                    $experstIds = ArrayHelper::getColumn($results, 'id');
                    $loadSearch = true;
                }
            }
        }
        
        if (!$loadSearch) {
            
            $experstIds = AuthorRoles::find()
                                    ->select('author_id')
                                    ->where(['role_id' => $expertRoleId])
                                    ->asArray()
                                    ->all();

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

