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
            [['authors', 'title', 'publisher', 'date', 'id', 'doi'], 'string']
        ];
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function getContent() {
        
        $content = "";
        $content .= 'AU - '.$this->authors." \n";
        $content .= 'TI - '.$this->title." \n";
        $content .= "PB - IZA World of Labor \n";
        $content .= 'VL - '.$this->id." \n";
        $content .= 'DO - '.$this->doi." \n";
        
        return $content;
    }
}
