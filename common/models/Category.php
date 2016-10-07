<?php

namespace common\models;

use kartik\tree\TreeView;
use Yii;
use yii\helpers\Html;

class Category extends \kartik\tree\models\Tree {

    /**
     * @var array the list of boolean value attributes
     */
    public static $boolAttribs = [
        'active',
        'filterable',
        'visible_in_menu'
    ];

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

    public function attributeLabels() {
        
        $module = TreeView::module();
        $keyAttribute = $nameAttribute = $leftAttribute = $rightAttribute = $depthAttribute = null;
        $treeAttribute = $iconAttribute = $iconTypeAttribute = null;

        extract($module->treeStructure + $module->dataStructure);
        $labels = [
            $keyAttribute => Yii::t('kvtree', 'ID'),
            $nameAttribute => Yii::t('kvtree', 'Title'),
            $leftAttribute => Yii::t('kvtree', 'Left'),
            $rightAttribute => Yii::t('kvtree', 'Right'),
            $depthAttribute => Yii::t('kvtree', 'Depth'),
            'description' => Yii::t('kvtree', 'Description'),
            'meta_keywords' => Yii::t('kvtree', 'Meta Keywords'),
            'meta_title' => Yii::t('kvtree', 'Meta Title'),
            'type' => Yii::t('kvtree', 'Type'),
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
        $nameAttribute = $descriptionAttribute = $typeAttribute = $metaTitleAttribute = $metaKeywordsAttribute = $urlKeyAttribute = null;

        extract($module->dataStructure);
        $attribs = array_merge([$nameAttribute, $descriptionAttribute, $typeAttribute, $metaTitleAttribute, $metaKeywordsAttribute, $urlKeyAttribute,], static::$boolAttribs);

        $rules = [
            [[$nameAttribute], 'required'],
            [$attribs, 'safe']
        ];
        
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
