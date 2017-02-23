<?php
namespace frontend\controllers\traits;

use Yii;
use common\models\AuthorRoles;
use common\modules\eav\helper\EavValueHelper;
use common\models\ExpertSearch;

trait ExpertTrait {
    
    protected $stepLimit = 3;
    
    protected function getLimit() {
        
        $limitDefault = Yii::$app->params['expert_limit'];
        $step = Yii::$app->request->get('step');
        $limit = $limitDefault;
        
        if (Yii::$app->request->getIsPjax()) {
            
            if ($step && intval($step)) {
                $limit = ($step * $this->stepLimit) + $limitDefault;
            }
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
    
    protected function getSearchResult(ExpertSearch $finds):array {

        return $finds->find()->select(['id'])
                             ->match($finds->search_phrase)
                             ->limit(500)
                             ->asArray()
                             ->all();
    }
    
    protected function getExpertIds(int $role):array {
        
        return AuthorRoles::find()->select('author_id')
                                    ->where(['role_id' => $role])
                                    ->asArray()
                                    ->all();
    }
}

