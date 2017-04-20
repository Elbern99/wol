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
            ['result', 'required'],
            [['result', 'creteria', 'filters', 'synonyms'], 'string'],
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
    
    public static function addNewResult($args = []) {

        $model = new static();
        $params = [
            'result' => ($args['result']) ? serialize($args['result']): null,
            'creteria' => ($args['creteria']) ? serialize($args['creteria']): null,
            'filters' => ($args['filters']) ? serialize($args['filters']) : null,
            'synonyms' => (is_array($args['synonyms'])) ? serialize($args['synonyms']) : null
        ];
        
        if ($model->load($params, '') && $model->save()) {
            Yii::$app->getSession()->set('search_result_id', $model->id);
            return $model;
        }
        
        return false;
    }
    
    public function mixSearchCreteriaArray($newCreteria) {
        
        $oldCreteria = unserialize($this->creteria);
        
        if (array_diff($oldCreteria['types'], $newCreteria['types'])) {
            $oldCreteria['types'] = $newCreteria['types'];
        }
        
        return $oldCreteria;
    }
    
    public static function refreshResult($model, $args = []) {
        
        if (!$model) {
            return self::addNewResult($args);
        }

        if ($args['result']) {
            $model->result = serialize($args['result']);
        }
        
        if ($args['creteria']) {
            $model->creteria = serialize($args['creteria']);
        }
        
        if ($args['filters']) {
            $model->filters = serialize($args['filters']);
        }
        
        if ($args['synonyms']) {
            $model->synonyms = serialize($args['synonyms']);
        }

        if ($model->save()) {
            Yii::$app->getSession()->set('search_result_id', $model->id);
            return $model;
        }
        
        return false;
    }
    
    public function removeExpiresSearchResult() {
        $expire = strtotime($this->createdAtExpires);
        $this->deleteAll(['>','created_at', $expire]);
    }

}
