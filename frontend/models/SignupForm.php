<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username = null;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $confirm_email;
    public $confirm_password;
    public $agree;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'max' => 255],
            ['first_name', 'required'],
            ['first_name', 'string', 'min' => 2, 'max' => 255],
            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['confirm_email', 'required'],
            ['confirm_email', 'string', 'max' => 255],
            ['confirm_email', 'compare', 'compareAttribute'=>'email', 'message'=>"Email don't match"],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'required'],
            ['confirm_password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Password don't match"],
            ['agree', 'required', 'requiredValue' => 1, 'message' => 'You do not agree with conditions']
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
