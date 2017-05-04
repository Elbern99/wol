<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_activation".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $created_at
 *
 * @property User $user
 */
class UserActivation extends \yii\db\ActiveRecord
{
    
    protected $subject = 'IZA World of Labor - Confirm Email Address';
    protected static $duration = "10 days";
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_activation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'created_at'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['token', 'new_email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'token' => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function addActivated(User $user) {
        
        $this->token = self::getToken();
        $this->user_id = $user->id;
        $this->created_at = time();
        
        if ($this->save()) {
            return $this->sendConfirmedEmail($user, $this->token);
        }

        return false;
    }
    
    public function resendConfirmedEmail(User $user) {

        $activated = UserActivation::find()->where(['user_id' => $user->id])->one();
                
        if (is_null($activated)) {
            $activated = new UserActivation();
            $activated->user_id = $user->id;
        }
        
        $activated->created_at = time();
        $activated->token = self::getToken();

        if ($activated->save()) {
            return $this->sendConfirmedEmail($user, $activated->token);
        }
        
        return false;
    }
    
    public function sendConfirmedEmail(User $user, string $token) {
        
        $body = Yii::$app->view->renderFile('@frontend/views/emails/confirmatedEmail.php',['user' => $user, 'token' => $token]);

        $job = new \UrbanIndo\Yii2\Queue\Job([
            'route' => 'mail/send', 
            'data' => [
                'to' => $this->new_email ? $this->new_email : $user->email,
                'from' => Yii::$app->params['supportEmail'], 
                'subject' => $this->subject, 
                'body' => $body
            ]
        ]);

        Yii::$app->queue->post($job);
        
        return true;
    }
    
    public function changeUserEmailVerification(User $user, string $newEmail) {
        
        $this->token = self::getToken();
        $this->user_id = $user->id;
        $this->created_at = time();
        $this->new_email = $newEmail;
        
        if ($this->save()) {
            return $this->sendConfirmedEmail($user, $this->token);
        }

        return false;
    }
    
    public function verifyToken($token) {

        try {
            
            $user = User::findOne($this->user_id);
            $dateVerificate = strtotime(self::$duration, $this->created_at);

            if ($dateVerificate < time()) {

                $this->created_at = time();
                $this->token = self::getToken();
                $this->save();

                $this->sendConfirmedEmail($user, $this->token);
                Yii::$app->session->setFlash('error', 'Token has expired you have been sent a new message');
                return false;
            }

            $user->activated = 1;

            if ($this->new_email) {
                $newslatter = Yii::$container->get('newsletter');
                $newslatter->getSubscriber($user->email);
                $newslatter->setAttribute('email', $this->new_email);
                $newslatter->updateAttribute();
                
                $user->email = $this->new_email;
            }

            $user->save();
            $this->delete();

            return $user;
            
        } catch (\yii\db\Exception $e) {
            return false;
        }
    }
    
    private static function getToken() {
        return Yii::$app->security->generateRandomString();
    }
}
