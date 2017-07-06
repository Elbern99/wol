<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use common\models\Article;
use common\modules\eav\Collection;
use yii\helpers\ArrayHelper;
use common\models\FavoritArticle;
use common\modules\eav\CategoryCollection;
use common\models\Author;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use frontend\models\Cite;
use common\modules\eav\helper\EavValueHelper;
use common\models\VersionsArticle;
/**
 * Site controller
 */
class VersionController extends Controller {
    
    use \frontend\components\articles\SubjectTrait;
    use \frontend\components\articles\ArticleTrait;
    
    protected function getVersionSlugModel($slug) {
        
        return  $model = VersionsArticle::find()
                    ->with([
                        'article'=> function($query) {
                            return $query->select(['id', 'seo', 'notices']);
                        },
                        'articleAuthors.author' => function($query) {
                            return $query->select(['id', 'avatar', 'url_key', 'author_key'])->where(['enabled' => 1])->asArray();
                        }, 
                        'articleCategories' => function($query) {
                            return $query->select(['category_id', 'article_id'])->asArray();
                        }
                    ])
                    ->where(['seo' => $slug, 'enabled' => 1])
                    ->one(); 
    }

    public function actionOnePager($slug) {
        $model = $this->getVersionSlugModel($slug);
        return $this->renderArticlePage('one-pager', $model, true);
    }

    public function actionFull($slug) {
        $model = $this->getVersionSlugModel($slug);
        return $this->renderArticlePage('full', $model);
    }
    
    public function actionLang($slug, $code) {
        $model = $this->getVersionSlugModel($slug);
        $this->setArticleLang($code);
        return $this->renderArticlePage('one-pager', $model, true, $code);
    }


}
        