<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url; 
use common\models\Settings;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * Video Manager Class Controller
 */
class SettingsController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        
        $settings = Settings::find()->all();
        $tabs = $this->renderTabs($settings);
        return $this->render('tabs', ['items' => $tabs]);
    }
    
    protected function renderTabs($items) {

        $tabs = [];
        $editUrl = 'settings/update/';
        $baseTemplatePath = $this->getViewPath();
                
        foreach ($items as $item) {
            
            $params = null;
            
            if ($item->value) {
                $params = unserialize($item->value);
            }
            
            $content = $this->renderFile($baseTemplatePath.'/types/'.$item->type.'.php', ['params' => $params]);
            
            $tabs[] = array(
                'label' => '<i class="glyphicon"></i> '.  ucfirst(str_replace('_', ' ', $item->name)),
                'content' => $this->renderFile($baseTemplatePath.'/form.php', ['postUrl' => Url::to([$editUrl,'id'=>$item->id]), 'type' => $content]),
            );
        }
        
        return $tabs;
    }
    
    public function actionUpdate($id) {
        
        $model = Settings::findOne($id);
        $serialiseData = [];
        
        if (!is_object($model)) {
            
            throw new NotFoundHttpException(Yii::t('app/text','The requested page does not exist.'));
        }
        
        $postData = Yii::$app->request->post();
        unset($postData['_csrf-backend']);
        
        if (isset($_FILES['image'])) {
            
            $filename = $model->upload($_FILES['image'], $model->name);

            if ($filename) {
                $serialiseData['image'] = $filename;
            } else {
                $value = ($model->value) ? unserialize($model->value) : [];
                
                if (isset($value['image'])) {
                    $serialiseData['image'] = $value['image'];
                }
            }
        }
        
        $serialiseData = array_merge($serialiseData, $model->validatePost($postData));

        if (!empty($serialiseData)) {
            $model->value = serialize($serialiseData);
            $model->save();
        }
        
        Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Setting save success'), false);
        return $this->redirect('@web/settings');
    }
    
}
