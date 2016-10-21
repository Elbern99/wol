<?php

namespace common\contracts;
/*
 * Url Rewrite public function
 */
interface IUrlRewrite {
    
    public function getRewriteByPath($current_path);
    public function autoCreateRewrite($params);
    public function autoRemoveRewrite($rewrite_paths);
    
}
