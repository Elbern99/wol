<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['value'], 'string'],
            [['type', 'name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
    
    public function getBaseFolder() {
        return '/uploads/settings/';
    }


    public function upload($file, $type) {

        $upload = UploadedFile::getInstanceByName('image');
        $validator = new \yii\validators\FileValidator();
        $validator->extensions = ['jpg', 'gif', 'png', 'bmp', 'jpeg', 'jepg', 'svg'];

        if (!$validator->validate($upload)) {
            return false;
        }
        
        $fileName = basename($file['name']);
        $targetFile = Yii::getAlias('@frontend').'/web'.$this->getBaseFolder().$type;
        

        if (!is_dir($targetFile)) {

            if (!FileHelper::createDirectory($targetFile, 0775, true)) {
                return false;
            }
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile.'/'.$fileName)) {
            return $this->getBaseFolder().$type.'/'.$fileName;
        }
        
        return false;
    }
    
    public function validatePost(array $items) {
        
        $validated = [];
        $validator = new \yii\validators\StringValidator();
        
        foreach ($items as $key => $item) {
            
            if ($validator->validate($item)) {
                $validated[$key] = $item;
            }
        }
        
        return $validated;
    }
}
