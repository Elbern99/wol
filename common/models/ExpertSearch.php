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
        
        if (empty($this->experience_type) && empty($this->expertise) && empty($this->language) && empty($this->author_country)) {
            return false;
        }
        
        $filters = [];

        if (!empty($this->experience_type)) {
            
            $experience = $this->filter['experience_type'];
            $values = $expert['experience_type'];
            $filters['experience_type'] = false;
            
            foreach ($this->experience_type as $id) {
                
                if (isset($experience[$id]) && (array_search($experience[$id], $values) !== false)) {
                    $filters['experience_type'] = true;
                    break;
                }
            }

        }
        
        if (!empty($this->expertise)) {
            
            $values = $expert['expertise'];
            $expertise = $this->filter['expertise'];
            $filters['expertise'] = false;
            
            foreach ($this->expertise as $id) {
                
                if (isset($expertise[$id]) && (array_search($expertise[$id], $values) !== false)) {
                    $filters['expertise'] = true;
                    break;
                }
            }
        }
        
        if (!empty($this->language)) {
            
            $values = $expert['language'];
            $language = $this->filter['language'];
            $filters['language'] = false;
            
            foreach ($this->language as $id) {
                
                if (isset($language[$id]) && (array_search($language[$id], $values) !== false)) {
                    $filters['language'] = true;
                    break;
                }
            }
        }
        
        if (!empty($this->author_country)) {
            
            $values = $expert['author_country'];
            $author = $this->filter['author_country'];
            $filters['author_country'] = false;
            
            foreach ($this->author_country as $id) {
                
                if (isset($author[$id]) && (array_search($author[$id], $values) !== false)) {
                    $filters['author_country'] = true;
                    break;
                }
            }
        }
        
        foreach ($filters as $filter) {
            if (!$filter) {
                return true;
            }
        }
        
        return false;     
    }
    
    public function getFilterAttributes() {

        return [
            'search_phrase' => $this->search_phrase,
            'experience_type' => is_array($this->experience_type) ? array_values($this->experience_type) : $this->experience_type,
            'expertise' => is_array($this->expertise) ? array_values($this->expertise) : $this->expertise,
            'language' => is_array($this->language) ? array_values($this->language) : $this->language,
            'author_country' => is_array($this->author_country) ? array_values($this->author_country) : $this->author_country
        ];
    }
}
