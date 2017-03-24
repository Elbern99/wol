<?php

namespace common\models;

use Yii;

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
