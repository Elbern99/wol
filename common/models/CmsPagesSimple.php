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
class CmsPagesSimple extends \yii\db\ActiveRecord implements \common\contracts\CmsPageTypeInterface
{
    use \common\helpers\FileUploadTrait;
    
    protected $files = [
        'backgroud'
    ];
    
    public function getFrontendPath() {
        return Yii::getAlias('@frontend').'/web/uploads/cms/backgroud';
    }
    
    public function getBackendPath() {
        return Yii::getAlias('@backend').'/web/uploads/cms/backgroud';
    }
            
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
    
    public function getContents($id) {

        $result = $this->find()
                        ->where(['page_id' => $id])
                        ->select(['backgroud', 'text'])
                        ->asArray()
                        ->one();

        if (isset($result["backgroud"]) && $result["backgroud"]) {
            $result["backgroud"] = '/uploads/cms/backgroud/'.$result["backgroud"];
        }
        
        return $result;
    }

}
