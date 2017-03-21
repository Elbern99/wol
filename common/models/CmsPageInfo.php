<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms_page_info".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $title
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 *
 * @property CmsPages $page
 */
class CmsPageInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_page_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'title', 'meta_title'], 'required'],
            [['page_id'], 'integer'],
            [['meta_keywords', 'meta_description', 'breadcrumbs'], 'string'],
            [['title', 'meta_title'], 'string', 'max' => 255],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsPages::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'page_id' => Yii::t('app', 'Page ID'),
            'title' => Yii::t('app', 'Title'),
            'meta_title' => Yii::t('app', 'Meta Title'),
            'meta_keywords' => Yii::t('app', 'Meta Keywords'),
            'meta_description' => Yii::t('app', 'Meta Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(CmsPages::className(), ['id' => 'page_id']);
    }
    
    public function getBreadcrumbsArray() {

        if (strlen($this->breadcrumbs)) {
            return unserialize($this->breadcrumbs);
        }
        
        return [];
    }
    
    public function beforeValidate() {

        $parent = parent::beforeValidate();

        if ($parent) {

            if (is_array($this->breadcrumbs)) {
                
                $breadcrumbs = [];
                
                foreach($this->breadcrumbs as $item) {
                    
                    if (!$item['url'] || !$item['label']) {
                        continue;
                    }
                    
                    $breadcrumbs[] = $item;
                }

                if (count($breadcrumbs)) {
                    $this->setAttribute('breadcrumbs', serialize($breadcrumbs));
                    return $parent;
                }
                
            }
            
            $this->setAttribute('breadcrumbs', null);
            
        }

        return $parent;
    }
}
