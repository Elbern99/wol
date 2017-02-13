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
    protected $filter = [];
    
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
            [['id'], 'unique'],
            [['id'], 'integer'],
            [['name', 'search_phrase'], 'string'],
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
            'name' => Yii::t('app', 'Value'),
        ];
    }
    
    public function setFilter($filter) {
        $this->filter = $filter;
    }
    
    public function filtered(array $expert): bool {
        
        $filtered = false;
        
        if (!empty($this->experience_type)) {
            
            $experience = $this->filter['experience_type'];
            $values = $expert['experience_type'];
            
            foreach ($this->experience_type as $id) {
                
                if (!isset($experience[$id]) || array_search($experience[$id], $values) === false) {
                    $filtered = true;
                    break;
                }
            }
            
            if ($filtered) {
               return $filtered;    
            }
        }
        
        if (!empty($this->expertise)) {
            
            $values = $expert['expertise'];
            $expertise = $this->filter['expertise'];
            
            foreach ($this->expertise as $id) {
                
                if (!isset($expertise[$id]) || array_search($expertise[$id], $values) === false) {
                    $filtered = true;
                    break;
                }
            }
            
            if ($filtered) {
               return $filtered;    
            }
        }
        
        if (!empty($this->language)) {
            
            $values = $expert['language'];
            $language = $this->filter['language'];
            
            foreach ($this->language as $id) {
                
                if (!isset($language[$id]) || array_search($language[$id], $values) === false) {
                    $filtered = true;
                    break;
                }
            }
            
            if ($filtered) {
               return $filtered;    
            }
        }
        
        if (!empty($this->author_country)) {
            
            $values = $expert['author_country'];
            $author = $this->filter['author_country'];
            
            foreach ($this->author_country as $id) {
                
                if (!isset($author[$id]) || array_search($author[$id], $values) === false) {
                    $filtered = true;
                    break;
                }
            }
        }
        
        return $filtered;     
    }
}
