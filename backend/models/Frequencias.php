<?php

namespace app\models;

use Yii;
use app\models\Ferias;

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
    public $mesInicial;
    public $nome;

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
            [['idusuario', 'nomeusuario', 'dataInicial', 'dataFinal', 'codigoOcorrencia','qtdDiasPagamento'], 'required'],
            [['idusuario','id'], 'integer'],
            [['dataInicial', 'dataFinal'], 'safe'],
            [['nomeusuario'], 'string', 'max' => 60],
            [['codigoOcorrencia'], 'string'],
            [['qtdDiasPagamento'], 'integer', 'min'=>0, 'max' => 30]
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
            'qtdDiasPagamento' => 'Dias para Pagamento'
        ];
    }

    //metodo alterado para listar todos os meses do anos no filtro das telas de detalhamento de frequencia
    public function mesFrequencias($idusuario){
        /*
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
        */
        $mes = array(1,2,3,4,5,6,7,8,9,10,11,12);

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

    public function frequenciasMes($idusuario,$mes){

        $frequencias = Frequencias::find()->where(["idusuario" => $idusuario])->all();
        $cont = 0;
        $arrayDias = array();

        for($i = 0; $i < count($frequencias) ; $i++ ){

            $mesSaida = date('m', strtotime($frequencias[$i]->dataInicial));

            if($mesSaida == $mes){
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

    public function frequenciasMesTodos($mes,$idusuario){

        $frequencias = Frequencias::find()->where(["idusuario" => $idusuario ])->all();
        $cont = 0;
        $arrayDias = array();

        for($i = 0; $i < count($frequencias) ; $i++ ){

            $mesInicial = date('m', strtotime($frequencias[$i]->dataInicial));

            if($mesInicial == $mes){
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

    //verifica se a data que se pretende cadastrar está dentro de [ou adentrando] um intervalo que já está cadastrado, caso esteja retorna 0 indicando que a data é Inválida, caso contrário retorna 1 indicando que a data é Válida
    public function verificarSeDataEhValida($idusuario,$ano,$mes,$dataInicial,$dataFinal){

        $frequencias = Frequencias::find()->select("j17_frequencias.*")->where(["idusuario" => $idusuario,"YEAR(dataInicial)" => $ano, "MONTH(dataInicial)" => $mes])->all();

        $totalfrequencias = count($frequencias);
        $valida=1;

        for ($i = 0; $i < $totalfrequencias; $i++){
            if($frequencias[$i]->id != $this->id){
                if ($frequencias[$i]->dataInicial <= $dataInicial and $frequencias[$i]->dataFinal >= $dataInicial){
                    $valida = 0;
                }if ($frequencias[$i]->dataInicial >= $dataInicial and $frequencias[$i]->dataInicial <= $dataFinal and $frequencias[$i]->dataFinal >= $dataFinal) {
                    $valida = 0;
                }if ($frequencias[$i]->dataInicial <= $dataInicial and $frequencias[$i]->dataInicial <= $dataFinal and $frequencias[$i]->dataFinal >= $dataFinal){
                    $valida = 0;
                }if($frequencias[$i]->dataInicial >= $dataInicial and $frequencias[$i]->dataFinal <= $dataFinal){
                    $valida = 0;
                }
            }    
        }

        return $valida;
    }

    public function verificarSeDataNaoConflitaComFerias($idusuario,$ano,$mes,$dataInicial,$dataFinal){

        $ferias = Ferias::find()->where(["idusuario" => $idusuario,"YEAR(dataSaida)" => $ano, "MONTH(dataSaida)" => $mes])->all();

        $totalferias = count($ferias);
        $valida=1;

        for ($i = 0; $i < $totalferias; $i++){
            if($ferias[$i]->id != $this->id){
                if ($ferias[$i]->dataSaida <= $dataInicial and $ferias[$i]->dataRetorno >= $dataInicial){
                    $valida = 0;
                }if ($ferias[$i]->dataSaida >= $dataInicial and $ferias[$i]->dataRetorno <= $dataFinal and $ferias[$i]->dataRetorno >= $dataFinal) {
                    $valida = 0;
                }if ($ferias[$i]->dataSaida <= $dataInicial and $ferias[$i]->dataSaida <= $dataFinal and $ferias[$i]->dataRetorno >= $dataFinal){
                    $valida = 0;
                }if($ferias[$i]->dataSaida >= $dataInicial and $ferias[$i]->dataRetorno <= $dataFinal){
                    $valida = 0;
                }
            }    
        }

        return $valida;
    }



    //verifica se a data que se pretende cadastrar está dentro de [ou adentrando] um intervalo que já está cadastrado, caso já exista registra o id do registro de frequência que já possui a data cadatrado, caso não exista retorna '-1', para ser tratado no controller
    /*public function verificarSeDataEhValida($idusuario,$ano,$mes,$dataInicial,$dataFinal){

        $frequencias = Frequencias::find()->select("j17_frequencias.*")->where(["idusuario" => $idusuario,"YEAR(dataInicial)" => $ano, "MONTH(dataInicial)" => $mes])->all();

        $totalfrequencias = count($frequencias);
        $id=-1;

		for ($i = 0; $i < $totalfrequencias; $i++){
			if (($dataInicial == $frequencias[$i]->dataInicial || $dataInicial == $frequencias[$i]->dataFinal) ||
				($dataInicial>$frequencias[$i]->dataInicial && $dataInicial <$frequencias[$i]->dataFinal)){
				$id = $frequencias[$i]->id;
			}if (($dataFinal == $frequencias[$i]->dataInicial || $dataFinal == $frequencias[$i]->dataFinal) ||
				($dataFinal>$frequencias[$i]->dataInicial && $dataFinal <$frequencias[$i]->dataFinal)) {
				$id = $frequencias[$i]->id;
			}if (($dataInicial<$frequencias[$i]->dataInicial && $dataFinal >$frequencias[$i]->dataInicial) ||
                    ($dataInicial<$frequencias[$i]->dataFinal && $dataFinal >$frequencias[$i]->dataFinal)){
                $id = $frequencias[$i]->id;
            }
		}        

        return $id;
    }*/

    public function contarOcorrencias($idusuario,$ano,$mes){
        $ocorrencias = Frequencias::find()->where("idusuario = '".$idusuario."' 
            AND YEAR(dataInicial) = ".$ano." AND MONTH(dataInicial) = ".$mes)->all();
        $this->diasPagar = count($ocorrencias);

        return $this->diasPagar;
    }

    public function contarDiasPagar($idusuario,$ano,$mes){
        $ocorrencias = Frequencias::find()->where("idusuario = '".$idusuario."' 
            AND YEAR(dataInicial) = ".$ano." AND MONTH(dataInicial) = ".$mes)->all();
        $dias = 30;
        $cont = 0;
        $contRepetidos = 0;

        $arrayDias = array();

        $this->totalOcorrencias = count($ocorrencias);

        for ($i = 0; $i < $this->totalOcorrencias; $i++) {
            $mesSaida = date('m', strtotime($ocorrencias[$i]->dataInicial));

            if ($mesSaida == $mes) {
                $datetime1 = new \DateTime($ocorrencias[$i]->dataInicial);
                $datetime2 = new \DateTime($ocorrencias[$i]->dataFinal);

                if($datetime1 == $datetime2){
                    $contRepetidos++;
                }

                $interval = $datetime1->diff($datetime2);
                $arrayDias[$cont] = abs($interval->format('%a'));
                $cont++;
            }
        }

        $resultado = $dias-(array_sum($arrayDias)+$contRepetidos);

        if ($resultado < 0){
            return 0;
        }else{
            return $resultado;
        }
    }

   public function pegarCodigoOcorrencia($id){
        $ocorrencia = Ocorrencias::find()->select('j17_ocorrencias.codigo')->from('j17_ocorrencias')->where(['id' => $id])->one();

        return $ocorrencia->codigo;
   }

   public function colunaNome(){
       $nome = User::find()->select("j17_user.nome, j17_user.id")->orderBy('nome');

       return $nome;
   }
}
