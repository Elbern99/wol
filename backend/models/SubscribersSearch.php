<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Newsletter;

/*
 * class for filters in article manager
 */

class SubscribersSearch extends Newsletter {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [ [ 'email' ], 'string', 'max' => 255 ],
            [ [ 'last_name' ], 'string', 'max' => 255 ],
            [ [ 'first_name' ], 'string', 'max' => 255 ],
            [ [ 'interest' ], 'integer', 'max' => 1 ],
            [ [ 'areas_interest' ], 'string', 'max' => 255 ],
            [ [ 'iza' ], 'integer', 'max' => 1 ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search( $params ) {
        $query = Newsletter::find()->orderBy( [ 'email' => SORT_DESC ] );

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider( [
            'query'      => $query,
            'pagination' => [ 'pageSize' => 30 ]
        ] );

        $this->load( $params );

        if ( ! $this->validate() ) {
            return $dataProvider;
        }


        if ( $this->email ) {
            $query->andFilterWhere( [ 'like', 'email', $this->email ] );
        }

        if ( $this->last_name ) {
            $query->andFilterWhere( [ 'like', 'last_name', $this->last_name ] );
        }

        if ( $this->first_name ) {
            $query->andFilterWhere( [ 'like', 'first_name', $this->first_name ] );
        }


        $query->andFilterWhere( [ 'interest' => $this->interest ] );


        if ( $this->areas_interest ) {
            $query->andFilterWhere( [ 'like', 'areas_interest', $this->areas_interest ] );
        }

        $query->andFilterWhere( [ 'iza' => $this->iza ] );


        return $dataProvider;
    }
}
