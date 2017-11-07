<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ferias;

/**
 * FeriasSearch represents the model behind the search form about `app\models\Ferias`.
 */
class FeriasSearch extends Ferias
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idusuario', 'tipo'], 'integer'],
            [['nome','nomeusuario', 'emailusuario', 'dataSaida', 'dataRetorno', 'dataPedido', 'adiantamentoDecimo', 'adiantamentoFerias'], 'safe'],
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
     
    
    public function search($params,$ano){
        

        //$query = User::find()->select("j17_user.nome, j17_user.id")->where(["j17_user.professor" => 1])->orderBy('nome');
        $query = User::find()->select("j17_user.nome, j17_user.id")->orderBy('nome');
        
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
            'tipo' => $this->tipo,
            'dataSaida' => $this->dataSaida,
            'dataRetorno' => $this->dataRetorno,
            'dataPedido' => $this->dataPedido,
            'adiantamentoDecimo' => $this->adiantamentoDecimo,
            'adiantamentoFerias' => $this->adiantamentoFerias,
        ]);

        $query->andFilterWhere(['like', 'nomeusuario', $this->nomeusuario])
            ->andFilterWhere(['like', 'emailusuario', $this->emailusuario])
            ->andFilterWhere(['like', 'nome', $this->nome]);
        
        return $dataProvider;
        
    }

    public function searchFuncionarios($params,$ano){
        

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
            'tipo' => $this->tipo,
            'dataSaida' => $this->dataSaida,
            'dataRetorno' => $this->dataRetorno,
            'dataPedido' => $this->dataPedido,
            'adiantamentoDecimo' => $this->adiantamentoDecimo,
            'adiantamentoFerias' => $this->adiantamentoFerias,
        ]);

        $query->andFilterWhere(['like', 'nomeusuario', $this->nomeusuario])
            ->andFilterWhere(['like', 'emailusuario', $this->emailusuario]);
        
        return $dataProvider;
        
    }
     
     
    public function searchMinhasFerias($params, $idUser ,$ano)
    {

            $query = Ferias::find()->select("j17_ferias.*, DATEDIFF((dataRetorno),(dataSaida)) as diferencaData")->where("idusuario = '".$idUser."' 
            AND YEAR(dataSaida) = ".$ano);

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
            'tipo' => $this->tipo,
            'dataSaida' => $this->dataSaida,
            'dataRetorno' => $this->dataRetorno,
            'dataPedido' => $this->dataPedido,
            'adiantamentoDecimo' => $this->adiantamentoDecimo,
            'adiantamentoFerias' => $this->adiantamentoFerias,
        ]);

        $query->andFilterWhere(['like', 'nomeusuario', $this->nomeusuario])
            ->andFilterWhere(['like', 'emailusuario', $this->emailusuario]);

        return $dataProvider;
    }

}
