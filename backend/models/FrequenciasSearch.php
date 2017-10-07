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
    public function search($params){


        //$query = User::find()->select("j17_user.nome, j17_user.id")->where(["j17_user.professor" => 1])->orderBy('nome');
        $query = Frequencias::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['nome'] = [
            'asc' => ['nome' => SORT_ASC],
            'desc' => ['nome' => SORT_DESC],
        ];

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

    public function searchFuncionarios($params){


        $query = User::find()->select("j17_user.nome, j17_user.id")->where(["j17_user.secretaria" => 1])->andWhere(["j17_user.professor" => 0])->orderBy('nome');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['nome'] = [
            'asc' => ['nome' => SORT_ASC],
            'desc' => ['nome' => SORT_DESC],
        ];

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
    
    public function searchMinhasFrequencias($params, $idUser ,$ano)
    {

        $query = Frequencias::find()->select("j17_frequencias.*, DATEDIFF((dataFinal),(dataInicial)) as diferencaData")->where("idusuario = '".$idUser."' 
            AND YEAR(dataInicial) = ".$ano);

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

        $dataProvider->sort->attributes['diferencaData'] = [
            'asc' => ['diferencaData' => SORT_ASC],
            'desc' => ['diferencaData' => SORT_DESC],
        ];

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
