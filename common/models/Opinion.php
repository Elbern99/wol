<?php

namespace common\models;

use yii\helpers\Html;

use Yii;
use yii\web\UploadedFile;

class Opinion extends \yii\db\ActiveRecord
{
    use \common\helpers\FileUploadTrait;
    
    protected $files = [
        'image_link',
    ];
    
    public $author_ids = [];
    
    protected $imagePath = '/web/uploads/opinions';


    public function getImagePath()
    {
        if ($this->image_link) {
            return Yii::getAlias('@frontend') . $this->imagePath . '/'. $this->image_link;
        }
  
        return null;
    }
    
    public function getFrontendPath() {
        return Yii::getAlias('@frontend').'/web/uploads/opinions';
    }
    
    public function getBackendPath() {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'opinions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_key', 'title'], 'required'],
            [['description', 'short_description'], 'string'],
            [['created_at', 'author_ids'], 'safe'],
            [['url_key'], 'match', 'pattern' => '/^[a-z0-9_\/-]+$/'],
            [['title'], 'string', 'max' => 255],
            [['url_key'], 'unique'],
            [['image_link'], 'file', 'extensions' => 'jpg, gif, png, bmp, jpeg, jepg', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url_key' => Yii::t('app', 'Url Key'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'short_decription' => Yii::t('app', 'Short Description'),
            'image_link' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'published_at' => Yii::t('app', 'Published At'),
            'author_ids' => Yii::t('app', 'Authors'),
        ];
    }
    
    public function loadAttributes()
    {
        $relatedAuthors = $this->getOpinionAuthors()->all();
        
        foreach ($relatedAuthors as $author) {
            $currentAuthor = $author->author;
            $this->author_ids[] = $currentAuthor->id; 
        }
    }
    
    public function getOpinionAuthors()
    {
        return $this->hasMany(OpinionAuthor::className(), ['opinion_id' => 'id']);
    }
    
    public function afterFind()
    {
        $this->created_at = new \DateTime($this->created_at);
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->checkImageLink();
            return true;
        } else {
            return false;
        }
        
    }
    
    public function checkImageLink()
    {
        $image =  UploadedFile::getInstance($this, 'image_link');
        
        if (!$image) {
            $currentItem = self::find()->where(['id' => $this->id])->one();
            if ($currentItem && $currentItem->image_link) {
                $this->image_link = $currentItem->image_link;
            }
        }
    }
    
    public function deleteImage()
    {
        if ($this->image_link) {
            if (file_exists($this->getImagePath())) {
                unlink($this->getImagePath());
            }
            
            Yii::$app->db->createCommand()
            ->update(self::tableName(), ['image_link' => ''], 'id = ' . $this->id)
            ->execute();
        }
    }
    
    public function saveFormatted()
    {
        if (!$this->validate())
            return false;
        
        $this->setCreatedAtDate();
        $this->initUploadProperty();
        $this->upload();
        
        if ($this->save()) {
            $this->saveAuthorsList();
        }
        
        return true;
    }
    
    protected function setCreatedAtDate()
    {
        $created_at = new \DateTime('now');
        $this->created_at = $created_at->format('Y-m-d');
    }
    
    public function authorsList()
    {
        $authors = Author::find()->orderBy('id desc')->all();
        $authorsList = [];
        foreach ($authors as $author) {
            $authorsList[$author->id] = $author->name; 
        }
        return $authorsList;
    }
    
    protected function saveAuthorsList()
    {
        OpinionAuthor::deleteAll(['=', 'opinion_id', $this->id]);
        
        $bulkInsertArray = [];
        
        if (is_array($this->author_ids)) {
            foreach ($this->author_ids as $id) {
                $bulkInsertArray[]=[
                    'opinion_id' => $this->id,
                    'author_id' => $id,
                ];
            }
            
            if (count($bulkInsertArray) > 0){
                $columnNamesArray = ['opinion_id', 'author_id'];
                $insertCount = Yii::$app->db->createCommand()
                               ->batchInsert(
                                       OpinionAuthor::tableName(), $columnNamesArray, $bulkInsertArray
                                 )
                               ->execute();
            }
        }
    }
    
    public function getAuthorsLink()
    {
        $relatedAuthors = $this->getOpinionAuthors()->all();
        $links = [];
        
        foreach ($relatedAuthors as $relatedAuthor) {
            $author = $relatedAuthor->author;
            $links[] = Html::a($author->name, Author::getAuthorUrl($author->url_key));
        }
        
        return implode(', ', $links);
    }
}
