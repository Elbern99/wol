<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DataSource;
use common\models\SourceTaxonomy;
use common\models\Taxonomy;
/*
 * class for filters in article manager
 */
class SourcesSearch extends DataSource
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['sourceTaxonomies'], 'safe']
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
        
        $taxonomyFilter = $params['SourcesSearch']['sourceTaxonomies'] ?? false;

        $query = DataSource::find()
                ->alias('s')
                ->innerJoin([SourceTaxonomy::tableName().' as st', 's.id = st.source_id']);
     
        if ($taxonomyFilter) {
            $query->where(['st.taxonomy_id' => $taxonomyFilter]);
        }
        
        $query->with(['sourceTaxonomies.taxonomy' => function($q) {
            return $q->select(['value']);
        }]);
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        return $dataProvider;
    }
}
