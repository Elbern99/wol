<?php

namespace common\models;

use kartik\tree\TreeView;
use Yii;
use yii\helpers\Html;
use common\contracts\TypeInterface;

class Category extends \kartik\tree\models\Tree {

    private $params = [
        1 => 'Article'
    ];
    
    private $type = null;
    
    /**
     * @var array the list of boolean value attributes
     */
    public static $boolAttribs = [
        'active',
        'filterable',
        'visible_in_menu',
        'system'
    ];
    
    public function init()
    {
        parent::init();
        
        $this->type = Yii::createObject(TypeInterface::class);
        $this->type->addTypes($this->params);
    }
    
    public function getType() {
        return $this->type;
    }

    public function afterSave($insert, $changedAttributes) {
        
        parent::afterSave($insert, $changedAttributes);

        if (!$this->system) {
            
            $rewrite_path = '/category/' . $this->id;
            $params = ['current_path'=>$this->getCategoryPath(), 'rewrite_path'=>$rewrite_path];
            \Yii::$container->get('Rewrite')->autoCreateRewrite($params);
        }
    }
    
    public function beforeDelete() {
        
        $cildrens = $this->children()->all();
        
        if (count($cildrens)) {
            
            $rewrite_paths = ['/category/' .$this->id];
            foreach ($cildrens as $children) {
                $rewrite_paths[] = '/category/' .$children->id;
            }
        } else {
            $rewrite_paths = '/category/' .$this->id;
        }

        \Yii::$container->get('Rewrite')->autoRemoveRewrite($rewrite_paths);
        return parent::beforeDelete();
    }
    
    public function getCategoryPath() {
        
        return $this->url_key;
    }

    public static function tableName() {
        return 'category';
    }

    public function initDefaults() {
        /**
         * @var Tree $this
         */
        $module = TreeView::module();
        $iconTypeAttribute = null;
        extract($module->dataStructure);

        foreach (static::$boolAttribs as $attr) {
            $this->setDefault($attr, false);
        }
    }

    public function removeNode($softDelete = true, $currNode = true) {
        /**
         * @var Tree $this
         * @var Tree $child
         */
        if ($softDelete == true) {
            $this->nodeRemovalErrors = [];
            $module = TreeView::module();
            extract($module->treeStructure);

            $children = $this->children()->all();
            foreach ($children as $child) {
                $child->active = false;
                if (!$child->save()) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    $this->nodeRemovalErrors[] = [
                        'id' => $child->$idAttribute,
                        'name' => $child->$nameAttribute,
                        'error' => $child->getFirstErrors()
                    ];
                }
            }

            if ($currNode) {
                $this->active = false;
                if (!$this->save()) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    /** @noinspection PhpUndefinedVariableInspection */
                    $this->nodeRemovalErrors[] = [
                        'id' => $this->$idAttribute,
                        'name' => $this->$nameAttribute,
                        'error' => $this->getFirstErrors()
                    ];
                    return false;
                }
            }
            return true;
        } else {
            return ($this->children()->count() || $this->isRoot()) ? $this->deleteWithChildren() : $this->delete();
        }
    }

    public function attributeLabels() {

        $module = TreeView::module();
        $keyAttribute = $nameAttribute = $leftAttribute = $rightAttribute = $depthAttribute = null;
        $typeAttribute = $metaTitleAttribute = $urlKeyAttribute = null;
        $treeAttribute = null;

        extract($module->treeStructure + $module->dataStructure);
        $labels = [
            $keyAttribute => Yii::t('kvtree', 'ID'),
            $nameAttribute => Yii::t('kvtree', 'Title'),
            $leftAttribute => Yii::t('kvtree', 'Left'),
            $rightAttribute => Yii::t('kvtree', 'Right'),
            $depthAttribute => Yii::t('kvtree', 'Depth'),
            $urlKeyAttribute => Yii::t('kvtree', 'Url Full Path'),
            $metaTitleAttribute => Yii::t('kvtree', 'Meta Title'),
            $typeAttribute => Yii::t('kvtree', 'Type'),
            'active' => Yii::t('kvtree', 'Active'),
            'filterable' => Yii::t('kvtree', 'Filterable'),
            'visible_in_menu' => Yii::t('kvtree', 'Visible In Menu'),
        ];

        if (!$treeAttribute) {
            $labels[$treeAttribute] = Yii::t('kvtree', 'Root');
        }

        return $labels;
    }

    public function rules() {

        $module = TreeView::module();
        $nameAttribute = $typeAttribute = $metaTitleAttribute = $urlKeyAttribute = null;

        extract($module->dataStructure);
        $attribs = array_merge([$nameAttribute, $typeAttribute, $metaTitleAttribute, $urlKeyAttribute], static::$boolAttribs);

        $rules = [
            [[$nameAttribute, $metaTitleAttribute, $urlKeyAttribute], 'required'],
            [$urlKeyAttribute, 'string', 'length' => [1, 255]],
            [$urlKeyAttribute, 'unique'],
            [$urlKeyAttribute, 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [$typeAttribute, 'integer'],
            [$attribs, 'safe']
        ];

        $rules[] = ['description', 'safe'];
        $rules[] = ['meta_keywords', 'safe'];

        if ($this->encodeNodeNames) {
            $rules[] = [
                $nameAttribute,
                'filter',
                'filter' => function ($value) {
                    return Html::encode($value);
                }
            ];
        }

        return $rules;
    }
    
}
