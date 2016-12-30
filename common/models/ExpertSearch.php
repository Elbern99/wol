<?php

namespace common\models;

use Yii;

/**
 * This is the model class for index "expertIndex".
 *
 * @property integer $id
 * @property string $value
 * @property string $email
 */
class ExpertSearch extends \yii\sphinx\ActiveRecord
{
    public $search_phrase;
    public $experience_type = [];
    public $expertise = [];
    public $language = [];
    public $author_country = [];
    
    /**
     * @inheritdoc
     */
    public static function indexName()
    {
        return 'expertIndex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search_phrase'], 'required'],
            [['id'], 'unique'],
            [['id'], 'integer'],
            [['value', 'email', 'search_phrase'], 'string'],
            [['experience_type', 'expertise', 'language', 'author_country'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
            'email' => Yii::t('app', 'Email'),
        ];
    }
}
