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

class AuthorsController extends Controller {
    
    public function actionExpert() {
        
        $limit = Yii::$app->params['expert_limit'];
        $limitPrev = Yii::$app->request->get('limit');

        if (isset($limitPrev) && intval($limitPrev)) {
            $limit += (int)$limitPrev;
        }

        
        
        $expertCollection = [];
        $roles = new Roles();
        $expertRoleId = $roles->getTypeByLabel('expert');
        
        $experstIds = AuthorRoles::find()
                                ->select('author_id')
                                ->where(['role_id' => $expertRoleId])
                                ->asArray()
                                ->all();


        if (!count($experstIds)) {
            return $this->render('expert', ['expertCollection' => $expertCollection, 'limit' => $limit, 'expertCount' => count($experstIds)]);
        }
        
        $experstIds = ArrayHelper::getColumn($experstIds, 'author_id');
        $experts = Author::find()
                        ->select(['id', 'avatar', 'author_key'])
                        ->where(['enabled' => 1, 'id' => $experstIds])
                        ->limit($limit)
                        ->all();

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

            $expertCollection[$expert->id] = [
                'affiliation' => $affiliation,
                'avatar' => ($expert->avatar) ? Author::getImageUrl($expert->avatar) : null,
                'name' => $name,
                'experience_type' => $experience_type,
                'expertise' => $expertise,
                'language' => $language,
                'author_country' => $author_country
            ];

        }
        
        return $this->render('expert', ['expertCollection' => $expertCollection, 'limit' => $limit, 'expertCount' => count($experstIds)]);
    }
}

