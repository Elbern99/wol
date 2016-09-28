<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use backend\models\SignupForm;
use yii\helpers\Console;
use common\models\UserInfo;

/*
 * Command example add admin user php yii add-user-admin -e=email@test.com -p=password -u=uname
 */
class AddUserAdminController extends Controller
{
    public $email;
    public $password;
    public $username;
    
    public function options($actionID)
    {
        return ['email', 'password', 'username'];
    }
    
    public function optionAliases()
    {
        return ['e' => 'email', 'p' => 'password', 'u' => 'username'];
    }
    
    public function actionIndex()
    {

        if ($this->email && $this->password && $this->username) {
            
            $signup = new SignupForm();
            $signup->username = $this->username;
            $signup->email = $this->email;
            $signup->password = $this->password;
            $signup->is_admin = 1;

            if ($signup->signup()) {
                $this->stdout("Used created success. L: {$signup->email} P: {$this->password}", Console::BG_GREEN);
                echo "\n";
                return 1;
            }
            
        } else {
            $this->stdout("Attribute password/username/email not exists.", Console::BG_RED);
            echo "\n";
        }
        
        $this->stdout("User can not added.", Console::BG_RED);
        echo "\n";

        return 0;
    }
}
