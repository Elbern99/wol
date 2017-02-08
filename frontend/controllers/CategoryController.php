<?php
namespace frontend\controllers;

use yii\web\Controller;
use common\models\Category;
use yii\web\NotFoundHttpException;
use common\modules\category\CategoryFactory;

class CategoryController extends Controller {
    
    public function actionIndex($id) {
        
        $category = Category::find()->where(['id' => $id, 'active' => 1])
                                    ->select([
                                        'url_key', 'title', 'root', 
                                        'meta_title', 'meta_keywords',
                                        'description', 'type', 'lvl', 'id',
                                        'lft', 'rgt'
                                    ]) 
                                    ->one();
        
        if (!is_object($category)) {
            throw new NotFoundHttpException('Page Not Found.');
        }
        
        $content = CategoryFactory::create($category);

        if (!$content) {
            throw new NotFoundHttpException('Page Not Found.');
        }
        
        return $this->render($content->getTamplate(), $content->getPageParams());
    }
}

