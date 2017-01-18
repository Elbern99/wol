<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
use yii\sphinx\MatchExpression;
use frontend\models\contracts\SearchInterface;
/**
 * Signup form
 */
class SearchForm extends Model implements SearchInterface
{
    use traits\SearchTrait;

    public $search_phrase;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['search_phrase', 'required'],
            ['search_phrase', 'string', 'min'=>3,'max'=>100],
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
}
