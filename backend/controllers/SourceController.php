<?php

namespace backend\controllers;

use Yii;
use common\models\DataSource;
use common\models\SourceTaxonomy;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use backend\models\DataSourceSearch;

/*
 * Data Source Manager Class Controller
 */
class SourceController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'delete'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        $searchModel = new DataSourceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id) {

        $sourceModel = DataSource::findOne($id);
        $adjacentSelected = $sourceModel->getSourceTaxonomies()->asArray()->all();
        $sourceModel->types = $adjacentSelected;
        
        if (Yii::$app->request->isPost) {
            
            $sourceTaxonomy = Yii::$app->request->post('SourceTaxonomy');

            if ($sourceModel->load(Yii::$app->request->post()) && $sourceModel->validate()) {

                if ($sourceModel->save()) {
                    /*SourceTaxonomy::deleteAll(['source_id' => $sourceModel->id]);
                    $bulkInsertArray = [];

                    foreach ($sourceModel->types as $type) {
                        $bulkInsertArray[] = array_merge(['source_id' => $sourceModel->id], $type);
                    }
                    
                    $result = Yii::$app->db->createCommand()
                    ->batchInsert(
                        SourceTaxonomy::tableName(), 
                        ['source_id', 'taxonomy_id', 'additional_taxonomy_id'], 
                        $bulkInsertArray
                    )
                    ->execute();*/
                    DataSource::removeDataSourcesCache();
                }
                
                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Source have been changed'), false);
                return $this->redirect('@web/source/view?id='.$id);
            }
        }
        
        return $this->render('view', ['sourceModel' => $sourceModel]);
    }
    
    public function actionDelete($id) {
        
        try {
            $model = DataSource::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            DataSource::removeDataSourcesCache();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Source was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Source did not delete!'));
        }
             
        return $this->redirect('@web/source');
    }
}
