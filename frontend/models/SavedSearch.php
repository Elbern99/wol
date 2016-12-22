<?php

namespace frontend\models;

use Yii;
use common\components\TimestampBehavior;
use frontend\models\contracts\SearchInterface;
use common\models\User;
/**
 * This is the model class for table "saved_search".
 *
 * @property integer $id
 * @property string $search_phrase
 * @property string $types
 * @property string $all_words
 * @property string $any_words
 * @property integer $created_at
 */
class SavedSearch extends \yii\db\ActiveRecord implements SearchInterface
{
    
    use \frontend\models\traits\SearchTrait;
    
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
        return 'saved_search';
    }
    
    public function getTypeNamesByIds() {
        
        $names = [];
        $ids = explode(',', $this->types);
        
        foreach ($ids as $id) {
            if (isset($this->headingLabel[$id])) {
                $names[] = $this->headingLabel[$id];
            }
        }
        
        return $names;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search_phrase', 'types'], 'required'],
            [['created_at'], 'integer'],
            [['search_phrase', 'all_words', 'any_words'], 'string', 'max' => 255],
            [['types'], 'string', 'max' => 50],
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
            'search_phrase' => Yii::t('app', 'Search Term'),
            'types' => Yii::t('app', 'Content Types'),
            'all_words' => Yii::t('app', 'Include'),
            'any_words' => Yii::t('app', 'Exclude'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
