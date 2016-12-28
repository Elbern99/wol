<?php
namespace common\helpers;

use common\contracts\IUrlRewrite;
use Yii;

/*
 * Helper class with public methods for work with url rewrite
 */
class UrlRewriteHelper implements IUrlRewrite {
    
    private $model = null;
    
    public function __construct(IUrlRewrite $model) {
        
        $this->model = $model;
    }
    
    public function getRewriteByPath($current_paths) {
        
        return $this->model->getRewriteByPath($current_paths);
    }
    
    public function autoCreateRewrite($params) {
        
        return $this->model->autoCreateRewrite($params);
    }
    
    public function autoRemoveRewrite($rewrite_path) {
        
        return $this->model->autoRemoveRewrite($rewrite_path);
    }
    
    /*
     * Method for change request path
     * @param object $request
     * 
     * @returm void 
     */
    public function rewritePath($request) {

        $result = Yii::$app->getUrlManager()->parseRequest($request);
        
        if ($result !== false) {
            list ($route, $params) = $result;

            if (Yii::$app->createController($route) === false) {
                
                $rewrite = $this->getRewriteByPath('/'.$request->getPathInfo());

                if (isset($rewrite['rewrite_path'])) {
                    $request->setPathInfo($rewrite['rewrite_path']);
                    $request->setUrl($rewrite['rewrite_path']);
                }
            }
        }
    }
}

