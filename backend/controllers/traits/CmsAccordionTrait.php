<?php

namespace backend\controllers\traits;

use common\models\CmsPageSections;
use Yii;
use yii\web\NotFoundHttpException;

/*
 * extension for cms page accordion type
 */
trait CmsAccordionTrait {
    
    /*
     * remove tab in accordion 
     * @property integer $id
     * @return html
     */
    public function actionSectionDelete($id) {

        try {
            
            $model = CmsPageSections::findOne($id);
            if (!is_object($model)) {
                throw new NotFoundHttpException(Yii::t('app/text', 'The requested page does not exist.'));
            }
            
            $pageId = $model->page_id;
            $model->delete();
            Yii::$app->getSession()->setFlash('section_open', 'accordion');
            Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Section was delete success!'));
            
        } catch (\yii\db\Exception $e) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Section did not delete!'));
        }

        return $this->redirect(['/cms/static-pages-view', 'id' => $pageId]);
    }
    
    /*
     * Edit tab
     * @property integer $id
     * @return html
     */
    public function actionSectionEdit($id) {

        $model = CmsPageSections::findOne($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->save()) {

                Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Section save success'), false);
                Yii::$app->getSession()->setFlash('section_open', 'accordion');
                return $this->redirect(['/cms/static-pages-view', 'id' => $model->page_id]);
            }
        }

        return $this->render('static-pages/edit/section_view', ['model' => $model, 'page' => $model->page_id]);
    }
    
    /*
     * Add Tab
     * @proprty integer $page_id
     * @return html
     */
    public function actionSectionAdd($page_id) {

        $model = new CmsPageSections();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            $model->setAttribute('page_id', $page_id);

            if ($model->validate()) {

                if ($model->save()) {
                    
                    Yii::$app->getSession()->setFlash('success', Yii::t('app/text', 'Section save success'), false);
                    Yii::$app->getSession()->setFlash('section_open', 'accordion');
                    return $this->redirect(['/cms/static-pages-view', 'id' => $page_id]);
                }
            }
        }

        return $this->render('static-pages/edit/section_view', ['model' => $model, 'page' => $page_id]);
    }
}
