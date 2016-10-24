<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cms_pages".
 *
 * @property integer $id
 * @property string $url
 * @property integer $enabled
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CmsPageInfo[] $cmsPageInfos
 * @property CmsPageSections[] $cmsPageSections
 */
class CmsPages extends \yii\db\ActiveRecord {

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'cms_pages';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['url', 'modules_id'], 'required'],
            [['enabled', 'created_at', 'updated_at', 'modules_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url Path'),
            'enabled' => Yii::t('app', 'Enabled'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsPageInfos() {
        return $this->hasOne(CmsPageInfo::className(), ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsPageSections() {
        return $this->hasMany(CmsPageSections::className(), ['page_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes) {
        
        parent::afterSave($insert, $changedAttributes);

        $rewrite_path = '/page/' . $this->id;
        $params = ['current_path' => $this->url, 'rewrite_path' => $rewrite_path];
        \Yii::$container->get('Rewrite')->autoCreateRewrite($params);
    }

    public function beforeDelete() {
        
        $rewrite_path = '/page/' . $this->id;
        \Yii::$container->get('Rewrite')->autoRemoveRewrite($rewrite_path);
        return parent::beforeDelete();
    }

}
