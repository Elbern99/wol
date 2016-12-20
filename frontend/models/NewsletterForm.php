<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class NewsletterForm extends Model
{
    use \frontend\models\traits\AreasOfInterest;
    
    public $email;
    public $first_name;
    public $last_name;
    public $items;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['first_name', 'required'],
            ['first_name', 'string', 'min' => 2, 'max' => 255],
            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function subscribe()
    {
        
    }
}
