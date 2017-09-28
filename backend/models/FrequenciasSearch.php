<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Frequencias;

/**
 * FrequenciasSearch represents the model behind the search form of `app\models\Frequencias`.
 */
class FrequenciasSearch extends Frequencias
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idusuario'], 'integer'],
            [['nomeusuario', 'dataInicial', 'dataFinal', 'codigoOcorrencia'], 'safe'],
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
        $query = Frequencias::find();

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
            'id' => $this->id,
            'idusuario' => $this->idusuario,
            'dataInicial' => $this->dataInicial,
            'dataFinal' => $this->dataFinal,
        ]);

        $query->andFilterWhere(['like', 'nomeusuario', $this->nomeusuario])
            ->andFilterWhere(['like', 'codigoOcorrencia', $this->codigoOcorrencia]);

        return $dataProvider;
    }
}
