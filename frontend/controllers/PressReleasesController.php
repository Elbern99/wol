<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\models\PressReleaseItem;
use common\models\Widget;
use common\models\CmsPages;
use frontend\models\CmsPagesRepository as Page;

/**
 * Site controller
 */
class PressReleasesController extends Controller {
    
    use \frontend\controllers\traits\PressReleasesTrait;
    
    protected $key = 'press-releases';

    public function actionIndex($month = null, $year = null)
    {
        $page = CmsPages::find()->select('id')->where(['url' => $this->key, 'enabled' => 1])->one();
        
        if (!is_object($page)) {
            throw new NotFoundHttpException();
        }
        
        $page = Page::getPageById($page->id);

        $limit = Yii::$app->params['news_limit'];

        if (Yii::$app->request->getIsPjax()) {
            $limitPrev = Yii::$app->request->get('limit');
            
            if (isset($limitPrev) && intval($limitPrev)) {
                $limit += (int)$limitPrev - 1;
            }

        }

        return $this->render('index', [
            'page' => $page,
            'news' => $this->getPressReleasesList($limit, $year, $month),
            'newsCount' => $this->getPressReleasesCount($year, $month),
            'category' => $this->getMainCategory(),
            'pressReleasesArchive' => $this->getPressReleasesArchive(),
            'limit' => $limit,
        ]);
    }
    
    public function actionView($slug)
    {
        $newsItem = PressReleaseItem::find()->andWhere(['url_key' => $slug])->one();
        
        if (!$newsItem) {
            throw new NotFoundHttpException();
        }
        
        $widgets = Widget::find()->where([
            'name' => ['stay_up_to_date', 'Socials'],
        ])->orderBy('id desc')->all();

        return $this->render('view', [
            'model' => $newsItem,
            'category' => $this->getMainCategory(),
            'widgets' => $widgets,
            'newsSidebar' => $this->getPressReleasesList(),
            'pressReleasesArchive' => $this->getPressReleasesArchive(),
        ]);
    }
    
}