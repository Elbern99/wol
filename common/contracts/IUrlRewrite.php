<?php

namespace common\contracts;

interface IUrlRewrite {
    public function getRewriteByPath($current_path);
}
