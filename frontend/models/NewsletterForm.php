<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class NewsletterForm extends Model 
{
    use \frontend\models\traits\AreasOfInterest;
    
    public $email;
    public $first_name;
    public $last_name;
    public $areas_interest = [];
    public $interest = 0;
    public $iza_world = 0;
    public $iza = 0;

    public function rules()
    {
        return [
            [['email', 'first_name', 'last_name'], 'required'],
            [['interest', 'iza_world', 'iza'], 'integer'],
            [['email', 'first_name', 'last_name'], 'string', 'max' => 255],
            ['areas_interest', 'safe'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => \common\models\Newsletter::className(), 'targetAttribute' => 'email', ],
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
        ];
    }

}
