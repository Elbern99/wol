<?php
namespace backend\components\category;

use kartik\tree\TreeViewAsset;

class CategoryManagerAsset extends TreeViewAsset
{
    
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-tree-manager']);
        $this->setupAssets('css', ['css/kv-tree-manager']);
        \kartik\base\AssetBundle::init();
    }

}