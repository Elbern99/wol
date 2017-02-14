<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Signup form
 */
class AuthorSearchForm extends Model
{   
    public $search;
    
    public function rules()
    {
        return [
            ['search', 'required'],
            ['search', 'string', 'max' => 255],
        ];
    }
}
