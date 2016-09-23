<?php
namespace common\helpers;

use common\contracts\IUrlRewrite;
use Yii;

class UrlRewriteHelper {
    
    private $model = null;
    
    public function __construct(IUrlRewrite $model) {
        $this->model = $model;
    }
    
    public function rewritePath($request) {

        $result = Yii::$app->getUrlManager()->parseRequest($request);
        
        if ($result !== false) {
            list ($route, $params) = $result;

            if (Yii::$app->createController($route) === false) {
                $rewrite = $this->model->getRewriteByPath($request->getUrl());

                if (isset($rewrite['rewrite_path'])) {
                    $request->setPathInfo($rewrite['rewrite_path']);
                    $request->setUrl($rewrite['rewrite_path']);
                }
            }
        }
    }
}

