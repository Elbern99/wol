<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NewsItem;

/*
 * class for filters in article manager
 */
class NewsSearch extends NewsItem
{
    public $created_at_from = '';
    public $created_at_to = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['created_at_from', 'created_at_to'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = NewsItem::find()->orderBy(['created_at' => SORT_DESC]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->created_at_from) {
            
            if (!$this->created_at_to) {
                $this->created_at_to = date('Y-m-d');
            }
            
            $query->andFilterWhere(['between', 'created_at', $this->created_at_from, $this->created_at_to]);
        }
        
        if ($this->title) {
            $query->andFilterWhere(['like', 'title', $this->title]);
        }

        return $dataProvider;
    }
}
