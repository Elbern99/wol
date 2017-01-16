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
        $source = DataSource::find()->orderBy('id');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $source, 'pagination' => ['pageSize' => 30]])]);
    }
    
    public function actionView($id = null) {
        
        if (is_null($id)) {
            $sourceModel = new DataSource();
            $adjacentModel = new SourceTaxonomy();
        } else {
            $sourceModel = DataSource::findOne($id);
            $adjacentModel = $sourceModel->getSourceTaxonomies()->all();
            var_dump($adjacentModel);exit;
        }
        
        if (Yii::$app->request->isPost) {
            
            $sourceTaxonomy = Yii::$app->request->post('SourceTaxonomy');
            
            if ($sourceModel->load(Yii::$app->request->post()) && $sourceModel->validate() && (isset($sourceTaxonomy['taxonomy_id']) && count($sourceTaxonomy))) {
                
                if ($sourceModel->save()) {
                    
                    SourceTaxonomy::deleteAll(['source_id' => $sourceModel->id]);
                    $bulkInsertArray = [];

                    foreach ($sourceTaxonomy['taxonomy_id'] as $source) {

                        $bulkInsertArray[] = ['source_id' => $sourceModel->id, 'taxonomy_id' => $source];
                    }
                    
                    Yii::$app->db->createCommand()
                    ->batchInsert(
                        SourceTaxonomy::tableName(), 
                        ['source_id', 'taxonomy_id'], 
                        $bulkInsertArray
                    )
                    ->execute();
                
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Source added success'), false);
                    return $this->redirect('@web/source');
                }
            }
        }
        
        return $this->render('view', ['sourceModel' => $sourceModel, 'adjacentModel' => $adjacentModel]);
    }
    
    public function actionDelete($id) {
        
        try {
            $model = DataSource::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
            }
            
            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text','Source was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text','Source did not delete!'));
        }
             
        return $this->redirect('@web/source');
    }
}
