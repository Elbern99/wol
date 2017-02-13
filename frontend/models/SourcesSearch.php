<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DataSource;
use common\models\SourceTaxonomy;
/*
 * class for filters in article manager
 */
class SourcesSearch extends DataSource
{
    public $sourceTaxonomies;
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
        $this->load($params);

        $taxonomyFilter = $this->sourceTaxonomies ?? false;

        $query = DataSource::find()
                ->alias('s')
                ->innerJoin(SourceTaxonomy::tableName().' as st', 's.id = st.source_id');

        if ($taxonomyFilter) {
            $query->where(['st.taxonomy_id' => $taxonomyFilter]);
        }
        
        $query->with('sourceTaxonomies.taxonomy');
        $query->with('sourceTaxonomies.additionalTaxonomy');
        $query->orderBy('s.source');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5]
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
