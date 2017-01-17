<?php

namespace common\models;

use Yii;
use common\contracts\SearchModelInterface;

/**
 * This is the model class for index "biographyIndex".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $url_key
 */
class BiographySearch extends \yii\sphinx\ActiveRecord implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'biographyIndex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'unique'],
            [['id'], 'integer'],
            [['name', 'value', 'url_key'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'url_key' => Yii::t('app', 'Url Key'),
        ];
    }
    
    public function getSearchResult($attributes) {
        
    }
}
