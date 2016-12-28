<?php

namespace common\models;

use common\modules\newsletter\contracts\NewsletterInterface;
use Yii;
use common\components\TimestampBehavior;

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
 */
class Newsletter extends \yii\db\ActiveRecord implements NewsletterInterface
{
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newsletter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'first_name', 'last_name'], 'required'],
            [['interest', 'iza_world', 'iza', 'created_at'], 'integer'],
            [['email', 'first_name', 'last_name', 'areas_interest'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
        
        $parent =parent::beforeValidate();
        
        if($parent) {
          
            if (is_array($this->areas_interest) && count($this->areas_interest)) {
                $this->setAttribute('areas_interest', implode(',', $this->areas_interest));
            }

        }
        
        return $parent;
    }
    
    public function getSubscriber(string $email) {
        $model = $this->find()->where(['email'=>$email])->one();
        
        if (is_object($model)) {
            if (is_string($model->areas_interest)) {
                $model->areas_interest = explode(',', $model->areas_interest);
            }
        }
        
        return $model;
    }
    
    public function setSubscriber(array $data) {

        if ($this->load($data, '') && $this->save()) {
            
            return true;
        }
        
        return false;
    }
    
}
