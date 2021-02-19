<?php

namespace common\models;

use common\modules\newsletter\contracts\NewsletterInterface;
use Yii;
use common\components\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "newsletter".
 *
 * @property integer $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $areas_interest
 * @property integer $interest
 * @property integer $iza_world
 * @property integer $iza
 * @property integer $created_at
 * @property string $code
 * @property integer $user_id
 *
 * @property NewsletterLogs[] $logs
 */
class Newsletter extends \yii\db\ActiveRecord implements NewsletterInterface {

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'newsletter';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['email', 'first_name', 'last_name'], 'required'],
            [['interest', 'iza_world', 'iza', 'created_at', 'user_id'], 'integer'],
            [['email', 'first_name', 'last_name', 'areas_interest', 'code'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'areas_interest' => Yii::t('app', 'Areas Interest'),
            'interest' => Yii::t('app', 'Interest'),
            'iza_world' => Yii::t('app', 'Iza World'),
            'iza' => Yii::t('app', 'Iza'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public function beforeValidate() {

        $parent = parent::beforeValidate();

        if ($parent) {

            if (is_array($this->areas_interest) && count($this->areas_interest)) {
                $this->setAttribute('areas_interest', implode(',', $this->areas_interest));
            }
        }

        return $parent;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(NewsletterLogs::class, ['id' => 'newsletter_logs_id'])
            ->viaTable('{{%newsletter_logs_users}}', ['newsletter_id' => 'id']);
    }

    public function getSubscriber(string $email) {
        $model = $this->find()->where(['email' => $email])->one();

        if (is_object($model)) {
            if (is_string($model->areas_interest)) {
                $model->areas_interest = explode(',', $model->areas_interest);
            }
        }

        return $model;
    }
    
    protected function getUserByEmail() {
        $user = User::find()->select('id')->where(['email' => $this->email])->one();
        
        if (is_object($user)) {
            return $user->id;
        }
        
        return null;
    }

    public function setSubscriber(array $data) {

        if ($this->load($data, '') && $this->validate()) {

            $this->user_id = $this->getUserByEmail();
            
            if ($this->iza || $this->iza_world || $this->interest) {
               
                $this->setAttribute('code', Yii::$app->security->generateRandomString());
                $this->sendSuccessEmail();
            }
            
            return $this->save(false);
        }

        return false;
    }

    public function sendSuccessEmail() {

        if (!is_null($this->id) && count($this->getOldAttributes())) {
            $mails = $this->forIssetSubscriber();
        } else {
            $mails = $this->forNewSubscriber();
        }

        foreach ($mails as $mail) {
            $this->sendEmail($mail['subject'], $mail['body']);
        }
    }

    protected function forIssetSubscriber() {

        $mails = [];

        if (!$this->getOldAttribute('iza_world') && !$this->getOldAttribute('iza')) {

            if ($this->getAttribute('iza_world') || $this->getAttribute('iza')) {

                $mails[] = [
                    'body' => Yii::$app->view->renderFile('@frontend/views/emails/subscribe.php', ['subscriber' => $this]),
                    'subject' => 'Welcome to the IZA World of Labor newsletter'
                ];
            }
        }

        if (($this->getAttribute('interest') && count($this->areas_interest)) && ($this->getOldAttribute('interest') != $this->getAttribute('interest'))) {
            
            $link = (Yii::$app->user->getIsGuest()) ? Url::to('/subscribe', true) : Url::to('/my-account', true);
            
            $mails[] = [
                'body' => Yii::$app->view->renderFile('@frontend/views/emails/articleAlert.php', ['link' => $link, 'subscriber' => $this]),
                'subject' => 'Article alerts for IZA World of Labor'
            ];
        }

        return $mails;
    }

    protected function forNewSubscriber() {

        $mails = [];
        

        if ($this->getAttribute('iza_world') || $this->getAttribute('iza')) {
            
            $mails[] = [
                'body' => Yii::$app->view->renderFile('@frontend/views/emails/subscribe.php', ['subscriber' => $this]),
                'subject' => 'Welcome to the IZA World of Labor newsletter'
            ];
        }
        var_dump("1zz", $mails);
        if ($this->getAttribute('interest') && count($this->areas_interest)) {
            
            $link = (Yii::$app->user->getIsGuest()) ? Url::to('/subscribe', true) : Url::to('/my-account', true);
            
            $mails[] = [
                'body' => Yii::$app->view->renderFile('@frontend/views/emails/articleAlert.php', ['link' => $link, 'subscriber' => $this]),
                'subject' => 'Article alerts for IZA World of Labor'
            ];
        }
        var_dump($mails);die();
        
        return $mails;
    }

    /**
     * Send welcome message to new or existing subscriber
     *
     * @param $subject
     * @param string $body
     * @return bool
     */
    protected function sendEmail($subject, string $body) {

        return Yii::$app->mailer
            ->compose()
            ->setFrom([Yii::$app->params['fromAddress'] => Yii::$app->params['fromName']])
            ->setTo($this->email)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();
    }
}
