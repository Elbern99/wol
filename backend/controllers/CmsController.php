<?php

namespace backend\controllers;

use Yii;

use common\models\CmsPages;
use common\models\CmsPageInfo;
use common\models\Modules;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use yii\helpers\ArrayHelper;
/*
 * CMS Page Manager Class Controller
 */
class CmsController extends Controller {

    use traits\CmsAccordionTrait,
        traits\CmsBaseTrait,
        traits\CmsWidgetTrait,
        traits\CmsSimpleTrait;
    
    
    
    public function behaviors() {
        
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['select-type', 'static-pages', 'static-pages-view', 
                            'static-pages-delete', 'section-add', 'section-edit', 
                            'section-delete', 'widget', 'simple'
                        ],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'static-pages-delete' => ['post'],
                    'section-delete' => ['post'],
                    'widget' => ['post'],
                    'simple' => ['post']
                ],
            ],
        ];
    }
    
    /*
     * init page type before add
     */
    public function actionSelectType() {
        
        $items = ArrayHelper::map(Modules::find()->select(['id', 'title'])->asArray()->all(), 'id', 'title');
        return $this->render('static-pages/edit/select_view', ['items' => $items, 'postUrl'=>'/cms/static-pages-view']);
    }
    
    /*
     *  action for add cms page
     */
    public function actionStaticPages() {

        $model = CmsPages::find()->alias('page')->with('cmsPageInfos')->orderBy('created_at');
        return $this->render('static-pages/index', ['dataProvider' => new ActiveDataProvider(['query' => $model, 'pagination' => ['pageSize' => 20]])]);
    }

    /*
     *  action for change cms page data
     */
    public function actionStaticPagesView($id = null) {

        if (is_null($id)) {
            $page = new CmsPages();

            $cmsType = Yii::$app->request->post('cms_type');

            if (!is_null($cmsType)) {
                
                if (!$cmsType) {
                    Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Cms Type did not set'));
                    return $this->goBack(['/cms/static-pages']);
                }
                
                $page->setAttribute('modules_id', $cmsType);
            }

            $page_info = new CmsPageInfo();
            
        } else {
            
            $page = CmsPages::findOne($id);

            if (!is_object($page)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }

            $page_info = CmsPageInfo::findOne(['page_id' => $page->id]);
        }

        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();
            if ($page->load($post) && $page->validate()) {
                if ($page->save()) {
                    if (!$page_info->page_id && isset($page->id)) {
                        $page_info->setAttribute('page_id', $page->id);
                    }
                }
            }

            if ($page_info->load($post) && $page_info->validate()) {
                if ($page_info->save(false)) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Page save success'), false);
                    return $this->redirect(['/cms/static-pages-view', 'id' => $page_info->page_id]);
                }
            }
        }

        return $this->render('static-pages/edit/view', ['items' => $this->getTabItems($page, $page_info)]);
    }

    /*
     * action for delete cms page
     */
    public function actionStaticPagesDelete($id) {

        try {

            $model = CmsPages::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }

            $model->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Page was delete success!'));
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Page did not delete!'));
        }

        return $this->redirect('@web/cms/static-pages');
    }

}
