<?php
namespace backend\models;

use Yii;
use common\components\TimestampBehavior;

/**
 * This is the model class for table "archive_log".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_at
 * @property resource $log
 */
class ArchiveLog extends \yii\db\ActiveRecord
{
    const STATUS_DONE = 200;
    const STATUS_ERROR = 400;
    
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
        return 'archive_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['log'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'log' => Yii::t('app', 'Log'),
        ];
    }
    
    public function addErrorLog($name, array $errors) {
        
        $this->name = $name;
        $this->status = self::STATUS_ERROR;
        $this->log = serialize($errors);
        
        return $this->save();
    }
    
    public function addSuccessLog($name) {
        
        $this->name = $name;
        $this->status = self::STATUS_DONE;
        
        return $this->save();
    }

}
