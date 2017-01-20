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
    
    protected $subject = 'IZA Confirm Email';
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
            [['token'], 'string', 'max' => 255],
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
    
    public function sendConfirmedEmail(User $user, string $token, $newEmail = null) {
        
        $body = Yii::$app->view->renderFile('@frontend/views/emails/confirmatedEmail.php',['user' => $user, 'token' => $token, 'newEmail' => $newEmail]);

        $job = new \UrbanIndo\Yii2\Queue\Job([
            'route' => 'mail/send', 
            'data' => [
                'to' => $user->email, 
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
        
        if ($this->save()) {
            return $this->sendConfirmedEmail($user, $this->token, $newEmail);
        }

        return false;
    }
    
    public static function verifyToken($token, $email = null) {

        $model = self::find()->where(['token' => $token])->one();

        if (!$model) {
            return false;
        }
        
        $user = User::findOne($model->user_id);
        $dateVerificate = strtotime(self::$duration, $model->created_at);

        if ($dateVerificate < time()) {
            
            $model->created_at = time();
            $model->token = self::getToken();
            $model->save();

            $model->sendConfirmedEmail($user, $model->token);
            Yii::$app->session->setFlash('error', 'Token has expired you have been sent a new message');
            return false;
        }
        
        $user->activated = 1;
        
        if ($email) {
            $user->email = $email;
        }
        
        $user->save();
        $model->delete();
        
        return $user;
    }
    
    private static function getToken() {
        return Yii::$app->security->generateRandomString();
    }
}
