<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Viecle;

/**
 * ViecleSearch represents the model behind the search form about `app\models\Viecle`.
 */
class ViecleSearch extends Viecle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VID', 'viecle_year', 'cc', 'seat', 'weight', 'owner'], 'integer'],
            [['viecle_type', 'plate_no', 'viecle_name', 'brand', 'model', 'body_code', 'engin_code', 'body_type'], 'safe'],
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
        $query = Viecle::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'VID' => $this->VID,
            'viecle_year' => $this->viecle_year,
            'cc' => $this->cc,
            'seat' => $this->seat,
            'weight' => $this->weight,
            'owner' => $this->owner,
        ]);

        $query->andFilterWhere(['like', 'viecle_type', $this->viecle_type])
            ->andFilterWhere(['like', 'plate_no', $this->plate_no])
            ->andFilterWhere(['like', 'viecle_name', $this->viecle_name])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'body_code', $this->body_code])
            ->andFilterWhere(['like', 'engin_code', $this->engin_code])
            ->andFilterWhere(['like', 'body_type', $this->body_type]);

        return $dataProvider;
    }
}
