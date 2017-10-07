<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_frequencias".
 *
 * @property int $id
 * @property int $idusuario
 * @property string $nomeusuario
 * @property string $dataInicial
 * @property string $dataFinal
 * @property string $codigoOcorrencia
 */
class Frequencias extends \yii\db\ActiveRecord
{

    public $totalOcorrencias;
    public $diasPagar;
    public $anoInicial;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_frequencias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idusuario', 'nomeusuario', 'dataInicial', 'dataFinal', 'codigoOcorrencia'], 'required'],
            [['idusuario'], 'integer'],
            [['dataInicial', 'dataFinal'], 'safe'],
            [['nomeusuario'], 'string', 'max' => 60],
            [['codigoOcorrencia'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idusuario' => 'Idusuario',
            'nomeusuario' => 'Nome',
            'dataInicial' => 'Data Inicial',
            'dataFinal' => 'Data Final',
            'codigoOcorrencia' => 'Código da Ocorrencia',
        ];
    }

    public function mesFrequencias($idusuario){

        if($idusuario == null){
            $mes_model = Frequencias::find()->select("MONTH(dataInicial) as mesInicial")->all();
        }else{

            $mes_model = Frequencias::find()->select("MONTH(dataInicial) as mesInicial")->where(["idusuario" => $idusuario])->all();
        }

        $mes = array (0 => date("m"));

        for($i=0; $i<count($mes_model); $i++){
            $mes[$i] = $mes_model[$i]->mesInicial;
        }


        $mes = array_unique($mes);
        rsort($mes);


        return $mes;

    }

    public function anosFrequencias($idusuario){

        if($idusuario == null){
            $anos_model = Frequencias::find()->select("YEAR(dataInicial) as anoInicial")->all();
        }else{

            $anos_model = Frequencias::find()->select("YEAR(dataInicial) as anoInicial")->where(["idusuario" => $idusuario])->all();
        }

        $anos = array (0 => date("Y"));

        for($i=0; $i<count($anos_model); $i++){
            $anos[$i] = $anos_model[$i]->anoInicial;
        }


        $anos = array_unique($anos);
        rsort($anos);


        return $anos;

    }

    public function frequenciasAno($idusuario,$ano){

        $frequencias = Frequencias::find()->where(["idusuario" => $idusuario])->all();
        $cont = 0;
        $arrayDias = array();

        for($i = 0; $i < count($frequencias) ; $i++ ){

            $anoSaida = date('Y', strtotime($frequencias[$i]->dataInicial));

            if($anoSaida == $ano){
                $datetime1 = new \DateTime($frequencias[$i]->dataInicial);
                $datetime2 = new \DateTime($frequencias[$i]->dataFinal);
                $interval = $datetime1->diff($datetime2);
                $arrayDias[$cont] =  abs($interval->format('%a'));
                $cont++;
            }

        }

        return array_sum($arrayDias) + $cont;

    }

    public function frequenciasAnoTodos($ano,$idusuario){

        $frequencias = Frequencias::find()->where(["idusuario" => $idusuario ])->all();
        $cont = 0;
        $arrayDias = array();

        for($i = 0; $i < count($frequencias) ; $i++ ){

            $anoInicial = date('Y', strtotime($frequencias[$i]->dataInicial));

            if($anoInicial == $ano){
                $datetime1 = new \DateTime($frequencias[$i]->dataInicial);
                $datetime2 = new \DateTime($frequencias[$i]->dataFinal);
                $interval = $datetime1->diff($datetime2);
                $arrayDias[$cont] =  abs($interval->format('%a'));
                $cont++;
            }

        }

        return array_sum($arrayDias);

    }

    public function verificarSeEhProfessor($id){

        $ehProfessor = User::find()->where(["id" => $id])->one()->professor;

        return $ehProfessor;

    }
}
