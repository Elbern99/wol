<?php

namespace common\models;

use Yii;
use common\contracts\IUrlRewrite;
/**
 * This is the model class for table "url_rewrite".
 *
 * @property integer $id
 * @property string $current_path
 * @property string $rewrite_path
 */
class UrlRewrite extends \yii\db\ActiveRecord implements IUrlRewrite
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'url_rewrite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['current_path', 'rewrite_path'], 'required'],
            [['current_path', 'rewrite_path'], 'string', 'max' => 255],
            [['current_path'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'current_path' => Yii::t('app', 'Current Path'),
            'rewrite_path' => Yii::t('app', 'Rewrite Path'),
        ];
    }
    
    public function getRewriteByPath($current_path) {
        return self::find()->where(['current_path'=>$current_path])->select(['rewrite_path'])->asArray()->one();
    }
    
    public function autoCreateRewrite($params) {
        
        if (isset($params['rewrite_path'])) {
            $rewrite = self::find()->where(['rewrite_path'=>$params['rewrite_path']])->one();
            
            if (!is_null($rewrite)) {
                $rewrite->load($params, '');
                
                if ($rewrite->validate()) {
                    
                    $rewrite->save();
                    return true;
                }
                
            }
        }
        
        $this->load($params, '');
        if ($this->validate()) {
            $this->save();
            return true;
        }
        
        return false;
    }
    
    public function autoRemoveRewrite($rewrite_paths) {
        
        try {
            
            if (is_array($rewrite_paths)) {
                $this->deleteAll(['rewrite_path' => $rewrite_paths]);
            } else {
                $model = $this->find()->where(['rewrite_path' => $rewrite_paths])->one();
                
                if (is_object($model)) {
                    $model->delete();
                }
            }
            
            return true;
        } catch(\yii\db\Exception $e) {
            var_dump($e->getMessage());exit;
        }
        
        return false;
    }
}
