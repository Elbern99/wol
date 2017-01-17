<?php
namespace frontend\controllers\traits;

use yii\web\NotFoundHttpException;
use common\models\CmsPages;
use frontend\models\CmsPagesRepository as Page;
use frontend\models\SourcesSearch;
use Yii;

trait SourcesTrait {
    
    
    public function getSourcePageData() {
        
        $homePage = CmsPages::find()->select('id')->where(['url' => 'source'])->one();
        
        if (!is_object($homePage)) {
            throw new NotFoundHttpException();
        }
        
        $homePage = Page::getPageById($homePage->id);
        
        $searchModel = new SourcesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return [
            'page' => $homePage,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ];
    }
}

