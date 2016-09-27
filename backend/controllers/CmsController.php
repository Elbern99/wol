<?php

namespace backend\controllers;

use Yii;
use common\models\CmsPages;
use common\models\CmsPageInfo;
use common\models\CmsPageSections;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/*
 * CMS Page Manager Class Controller
 */

class CmsController extends Controller {

    protected $filePagePath = "@backend/views/cms/components/page_edit.php";
    protected $fileSectionsPath = "@backend/views/cms/components/sections_grid.php";

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'delete', 'section-add', 'section-edit', 'section-delete'],
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'section-delete' => ['post']
                ],
            ],
        ];
    }

    private function getTabItems($page, $page_info, $sections = null) {

        $items = array();

        $sectionOpen = Yii::$app->session->getFlash('section_open');

        $items[] = [
            'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Page'),
            'content' => $this->renderFile($this->filePagePath, ['page' => $page, 'page_info' => $page_info]),
            'active' => ($sectionOpen) ? false : true
        ];

        if (!is_null($sections)) {
            $items[] = [
                'label' => '<i class="glyphicon"></i> '.Yii::t('app/text','Sections'),
                'content' => $this->renderFile($this->fileSectionsPath, [
                    'dataProvider' => new ActiveDataProvider(['query' => $sections, 'pagination' => ['pageSize' => 20]]),
                    'page_id' => $page->id
                ]),
                'active' => ($sectionOpen) ? true : false
            ];
        }

        return $items;
    }

    public function actionIndex() {

        $model = CmsPages::find()->alias('page')->with('cmsPageInfos')->orderBy('created_at');
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $model, 'pagination' => ['pageSize' => 20]])]);
    }

    public function actionSectionAdd($page_id) {

        $model = new CmsPageSections();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $model->setAttribute('page_id', $page_id);

            if ($model->validate()) {

                if ($model->save()) {
                    
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Section save success'), false);
                    Yii::$app->getSession()->setFlash('section_open', true);
                    return $this->redirect(['/cms/view', 'id' => $page_id]);
                }
            }
        }

        return $this->render('edit/section_view', ['model' => $model, 'page' => $page_id]);
    }

    public function actionSectionEdit($id) {

        $model = CmsPageSections::findOne($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {

                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Section save success'), false);
                Yii::$app->getSession()->setFlash('section_open', true);
                return $this->redirect(['/cms/view', 'id' => $model->page_id]);
            }
        }

        return $this->render('edit/section_view', ['model' => $model, 'page' => $model->page_id]);
    }

    public function actionView($id = null) {

        if (is_null($id)) {
            $page = new CmsPages();
            $page_info = new CmsPageInfo();
            $sections = null;
        } else {
            $page = CmsPages::findOne($id);

            if (!is_object($page)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }

            $page_info = CmsPageInfo::findOne(['page_id' => $page->id]);
            $sections = CmsPageSections::find()->where(['page_id' => $page->id]);
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

                if ($page_info->save()) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Page save success'), false);
                    return $this->redirect(['/cms/view', 'id' => $page_info->page_id]);
                }
            }
        }

        return $this->render('edit/view', ['items' => $this->getTabItems($page, $page_info, $sections)]);
    }
    
    public function actionSectionDelete($id) {

        try {
            
            $model = CmsPageSections::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }
            
            $pageId = $model->page_id;
            $model->delete();
            Yii::$app->getSession()->setFlash('section_open', true);
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Section was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Section did not delete!'));
        }

        return $this->redirect(['/cms/view', 'id' => $pageId]);
    }

    public function actionDelete($id) {

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

        return $this->redirect('@web/cms');
    }

}
