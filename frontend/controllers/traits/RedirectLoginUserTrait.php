<?php
namespace frontend\controllers\traits;

use common\models\Article;
use Yii;

trait RedirectLoginUserTrait {
    
    protected $default = '/my-account';
    
    public function getLoginRedirect() {
        
        $articleId = Yii::$app->session->get('article_redirect');
        
        if ($articleId) {
            $article = Article::find()->select('seo')->where(['id' => $articleId, 'enabled' => 1])->asArray()->one();
            
            Yii::$app->session->remove('article_redirect');
            
            if (is_array($article)) {
                return '/articles/'.$article['seo'];
            }
        }
        
        return $this->default;
    }
}