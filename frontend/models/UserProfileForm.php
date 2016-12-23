<?php
namespace frontend\models;

use yii\base\Model;
use Yii;
/**
 * Signup form
 */
class UserProfileForm extends Model
{
    use \common\helpers\FileUploadTrait;
    
    protected $user;
    
    protected $files = [
        'avatar'
    ];
    
    public $username = null;
    public $email;
    public $password;
    public $password_old;
    public $first_name;
    public $last_name;
    public $avatar;
    
    public function __construct() {
        
        $this->user = Yii::$app->user->identity;
        $this->load($this->user->getAttributes(), '');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'string', 'max' => 255],
            ['first_name', 'required'],
            ['first_name', 'string', 'min' => 2, 'max' => 255],
            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['password', 'safe'],
            ['password_old', 'safe'],
            [['avatar'], 'file', 'extensions' => 'jpg, gif, png, bmp, jpeg, jepg', 'skipOnEmpty' => true],
        ];
    }

    public function getFrontendPath() {
        return Yii::getAlias('@frontend').'/web/uploads/users/avatar';
    }
    
    public function getAvatarUrl($avatar) {
        return '/uploads/users/avatar/'.$avatar;
    }
    
    public function getBackendPath() {
        return '';
    }
    
    public function saveAvatar() {
        
        $this->user->avatar = $this->avatar;
        
        if ($this->user->save(false)) {
            return true;
        }
        
        return false;
    }
    
    public function saveUserData() {

        $this->user->first_name = $this->first_name;
        $this->user->last_name = $this->last_name;
        $this->user->email = $this->email;

        if ($this->password) {
            
            if (!$this->checkOldPassword()) {
                return false;
            }
            
            $this->user->setPassword($this->password);
        }
        
        if ($this->user->save()) {
            return true;
        }
        
        return false;
    }
    
    public function checkOldPassword() {

        if(!$this->user->validatePassword($this->password_old)) {
             Yii::$app->getSession()->setFlash('error', Yii::t('app/text', 'Old password is incorrect'), false);
             return false;
        }
        
        return true;
    }

}
