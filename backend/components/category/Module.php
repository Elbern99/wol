<?php
namespace backend\components\category;

use kartik\tree\Module as BaseModule;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class Module extends BaseModule {
    
    public $controllerNamespace = 'kartik\tree\controllers';
    
    public $treeViewSettings = [
        'nodeView' => '@backend/views/category/_form',
        'nodeAddlViews' => [
            self::VIEW_PART_1 => '',
            self::VIEW_PART_2 => '',
            self::VIEW_PART_3 => '',
            self::VIEW_PART_4 => '',
            self::VIEW_PART_5 => '',
        ]
    ];
    
    public function init()
    {
        $this->_msgCat = 'kvtree';
        \kartik\base\Module::init();
        $this->treeStructure += [
            'treeAttribute' => 'root',
            'leftAttribute' => 'lft',
            'rightAttribute' => 'rgt',
            'depthAttribute' => 'lvl',
        ];
        $this->dataStructure += [
            'keyAttribute' => 'id',
            'nameAttribute' => 'title',
            'descriptionAttribute' => 'description',
            'typeAttribute' => 'type',
            'metaTitleAttribute' => 'meta_title',
            'metaKeywordsAttribute' => 'meta_keywords',
            'urlKeyAttribute' => 'url_key',
        ];
        $nodeActions = ArrayHelper::getValue($this->treeViewSettings, 'nodeActions', []);
        $nodeActions += [
            self::NODE_MANAGE => Url::to(['/treemanager/node/manage']),
            self::NODE_SAVE => Url::to(['/treemanager/node/save']),
            self::NODE_REMOVE => Url::to(['/treemanager/node/remove']),
            self::NODE_MOVE => Url::to(['/treemanager/node/move']),
        ];

        $this->treeViewSettings['nodeActions'] = $nodeActions;
    }
    
}
