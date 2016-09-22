<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "url_rewrite".
 *
 * @property integer $id
 * @property string $current_path
 * @property string $rewrite_path
 */
class UrlRewrite extends \yii\db\ActiveRecord
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
}
