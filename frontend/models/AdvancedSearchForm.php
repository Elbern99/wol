<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use frontend\models\contracts\SearchInterface;
/**
 * Signup form
 */
class AdvancedSearchForm extends Model implements SearchInterface
{
    use traits\SearchTrait;
    
    public $types;
    public $search_phrase;
    public $exact_phrase;
    public $all_words;
    public $one_more_words;
    public $any_words;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['types', 'required'],
            ['search_phrase', 'required'],
            ['search_phrase', 'string', 'min'=>3,'max'=>255],
            [['exact_phrase', 'all_words', 'one_more_words', 'any_words'], 'string', 'max'=>100],
        ];
    }
    
    /*
     * Seach by ajax for pre result
     */
    public function searchAjax() {

        $types = Yii::$app->params['search'];
        $result = [];
        
        foreach($this->getheadingModelFilter() as $modelType) {
            
            if (!isset($types[$modelType])) {
                throw new \Exception('Search Model Not Found');
            }
            
            $class = $types[$modelType];
            $data = $class::getSearchAjaxResult($this->search_phrase);

            foreach ($data as $d) {
                $result[] = array_merge(['type' => $modelType], $d);
            }

        }
        
        return $result;
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function search() {
        
        $types = Yii::$app->params['search'];
        $result = [];

        foreach($this->types as $type) {
            
            $modelType = $this->headingModel[$type];
            
            if (!isset($types[$modelType])) {
                throw new \Exception('Search Model Not Found');
            }
            
            $class = $types[$modelType];
            $data = $class::getSearchResult($this->getAttributes());

            foreach ($data as $d) {
                $result[] = [
                    'type' => $modelType,
                    'id' => $d['id']
                ];
            }

        }
        
        return $result;
    }
}
