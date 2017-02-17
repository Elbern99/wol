<?php

namespace common\models;

use Yii;
use common\components\TimestampBehavior;

/**
 * This is the model class for table "search_result".
 *
 * @property integer $id
 * @property string $result
 * @property string $creteria
 * @property resource $filters
 * @property integer $created_at
 */
class SearchResult extends \yii\db\ActiveRecord
{
    private $createdAtExpires = '-2 hour';
    
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
        return 'search_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result', 'creteria', 'filters'], 'string'],
            [['created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'result' => Yii::t('app', 'Result'),
            'creteria' => Yii::t('app', 'Creteria'),
            'filters' => Yii::t('app', 'Filters'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
    
    public static function addNewResult($result = null, $creteria = null, $filters = null) {

        $model = new static();
        $params = [
            'result' => ($result) ? serialize($result): $result,
            'creteria' => ($creteria) ? serialize($creteria): $creteria,
            'filters' => ($filters) ? serialize($filters) : $filters
        ];

        if ($model->load($params, '') && $model->save()) {
            Yii::$app->getSession()->set('search_result_id', $model->id);
            return $model->id;
        }
        
        return false;
    }
    
    public static function refreshResult($id, $result = null, $creteria = null, $filters = null) {
        
        $model = self::find()->where(['id' => $id])->one();

        if (!$model) {
            return self::addNewResult($result, $creteria, $filters);
        }
        
        if ($result) {
            $model->result = serialize($result);
        }
        
        if ($creteria) {
            $model->creteria = serialize($creteria);
        }
        
        if ($filters) {
            $model->filters = serialize($filters);
        }
        
        if ($model->save()) {
            Yii::$app->getSession()->set('search_result_id', $model->id);
            return $model->id;
        }
        
        return false;
    }
    
    public function removeExpiresSearchResult() {
        $expire = strtotime($this->createdAtExpires);
        $this->deleteAll(['>','created_at', $expire]);
    }

}
