<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "synonyms_search".
 *
 * @property integer $id
 * @property string $synonyms
 */
class SynonymsSearch extends \yii\db\ActiveRecord
{
    private static $words = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'synonyms_search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['synonyms'], 'required'],
            [['synonyms'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'synonyms' => Yii::t('app', 'Synonyms'),
        ];
    }
    
    public function convertToString() {
        
        if (is_array($this->synonyms)) {
            $data = ArrayHelper::getColumn($this->synonyms, 'synonym');
            $this->synonyms = str_replace(' ', '',implode(',', $data));
        }
    }
    
    public static function getSynonymWords() {
        return self::$words;
    }
    
    public static function getSynonymsFormatArray(array $words):array {
        
        $synonyms = [];
        $cnt = 0;
        
        foreach ($words as $word) {
            
            if (strlen($word) > 2) {
                $synonym = self::find()->andFilterWhere(['like', 'synonyms', $word])->asArray()->one();

                if (isset($synonym['synonyms'])) {
                    $synonyms[] = '('.str_replace(',', '|', $synonym['synonyms']).')';
                    self::$words = array_merge(self::$words, explode(',', $synonym['synonyms']));
                    $cnt++;
                } else {
                     $synonyms[] = $word;
                }
            }
        }
        
        if ($cnt) {
            return $synonyms;
        }
        
        return [];
    }
}
