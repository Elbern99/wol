<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use Yii;
use common\models\UserActivation;

/**
 * Signup form
 */
class SignupForm extends Model
{
    use \frontend\models\traits\AreasOfInterest;
    
    public $username = null;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $confirm_email;
    public $confirm_password;
    public $agree;
    public $items = null;
    public $newsletter = 0;
    public $errorMessage = false;
    public $reCaptcha;
    
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
            ['agree', 'required', 'requiredValue' => 1, 'message' => 'You do not agree with conditions'],
            ['items', 'safe'],
            ['newsletter', 'safe'],

            ['reCaptcha', \himiklab\yii2\recaptcha\ReCaptchaValidator2::class, 'uncheckedMessage' => 'Please confirm that you are not a bot.']
            
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
        $activated = new UserActivation();
        $user->username = null;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($user->save()) {

            $subscriberId = null;
            
            if ($this->newsletter && is_array($this->items)) {
                
                $newsletter = new NewsletterForm();
                $newsletter->email = $this->email;
                $newsletter->first_name = $this->first_name;
                $newsletter->last_name = $this->last_name;
                $newsletter->areas_interest = $this->items;
                $newsletter->interest = 1;
                
                try {
                    
                    if ($newsletter->validate()) {

                        $obj = Yii::$container->get('newsletter');
                        $obj->getSubscriber($newsletter->email);
                        $obj->setSubscriber($newsletter->getAttributes());
                        $subscriberId = $obj->getAttribute('code');
                    }
                    
                } catch(\yii\db\Exception $e) {
                    $this->errorMessage[] = 'Subscription is currently unavailable, try again later.';
                }
                
            }

            if (!$activated->addActivated($user)) {
                $this->errorMessage[] = 'We can not send confirmation email, Please try login later.';
            }
            
            return $user;
        }
        
        return null;
    }

    /**
     * Send welcome email to new user.
     *
     * @param $subscriberId
     * @param $user
     * @return bool
     */
    public function sendRegisteredEmail($subscriberId, $user)
    {
        return Yii::$app->mailer
            ->compose('@frontend/views/emails/registered.php', [
                'subscriber' => $subscriberId,
                'user' => $user
            ])
            ->setFrom([Yii::$app->params['fromAddress'] => Yii::$app->params['fromName']])
            ->setTo($this->email)
            ->setSubject(Yii::t('app', 'Welcome to IZA World of Labor'))
            ->send();
    }
}
