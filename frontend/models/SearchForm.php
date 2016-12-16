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
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function search($fields = []) {

        $types = Yii::$app->params['search'];
        $result = [];
        
        if (array_search('id', $fields) === false) {
            $fields = array_merge($fields, ['id']);
        }
        
        foreach($this->getheadingModelFilter() as $modelType) {
            
            if (!isset($types[$modelType])) {
                throw new \Exception('Search Model Not Found');
            }
            
            $class = $types[$modelType];

            $match = new MatchExpression();
            $searchPhrase = str_replace(' ', ' ?* ', Yii::$app->sphinx->escapeMatchValue($this->search_phrase));
            $match->match($searchPhrase);

            $data = $class::find()
                            ->select($fields)
                            ->match($match)
                            ->asArray()
                            ->all();

            foreach ($data as $d) {
                $result[] = array_merge(['type' => $modelType], $d);
            }

        }
        
        return $result;
    }
}
