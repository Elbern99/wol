<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms_pages_simple".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $backgroud
 * @property string $text
 *
 * @property CmsPages $page
 */
class CmsPagesSimple extends \yii\db\ActiveRecord
{
    
    const IMAGE_PATH = 'cms/backgroud';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_pages_simple';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id'], 'required'],
            [['page_id'], 'integer'],
            [['text'], 'string'],
            [['backgroud'], 'safe'],
            [['backgroud'], 'file', 'extensions' => 'jpg, gif, png, bmp, jpeg, jepg', 'skipOnEmpty' => true],
            [['page_id'], 'unique'],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsPages::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }
    
    public function upload() {

        if (is_object($this->backgroud)) {
            
            $imageName = Yii::$app->getSecurity()->generateRandomString(9);
            $imageName .= '.'. $this->backgroud->extension;
            $this->backgroud->name = $imageName;
            $filePath = Yii::getAlias('@backend').'/web/uploads/' . self::IMAGE_PATH . '/'.$this->backgroud->name;

            if ($this->backgroud->saveAs($filePath)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'page_id' => Yii::t('app', 'Page ID'),
            'backgroud' => Yii::t('app', 'Backgroud'),
            'text' => Yii::t('app', 'Text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(CmsPages::className(), ['id' => 'page_id']);
    }
}
