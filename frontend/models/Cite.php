<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use Yii;

/**
 * Signup form
 */
class Cite extends Model
{
    use \frontend\models\traits\AreasOfInterest;
    
    public $authors;
    public $title;
    public $publisher;
    public $date;
    public $id;
    public $doi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['authors', 'title', 'publisher', 'date', 'id', 'doi'], 'required'],
            [['title', 'publisher', 'date', 'id', 'doi'], 'string']
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function getContent() {
        $names = [];
        
        foreach ($this->authors as $author) {
            $name = $author['name']['last_name'];
            if ($author['name']['first_name']) {
                $name .= ' '.ucfirst(substr($author['name']['first_name'], 0, 1)).'.';
            }
            $names[] = $name;         
        }

        $content = "";
        $content .= 'AU - '.implode(', ', $names)." \n";
        $content .= 'TI - '.$this->title." \n";
        $content .= "PB - IZA World of Labor \n";
        $content .= 'VL - '.$this->id." \n";
        $content .= 'DO - '.$this->doi." \n";
        
        return $content;
    }
}
