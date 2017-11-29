<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_ferias".
 *
 * @property integer $id
 * @property integer $idusuario
 * @property string $nomeusuario
 * @property string $emailusuario
 * @property integer $tipo
 * @property string $dataSaida
 * @property string $dataRetorno
 * @property string $dataPedido
 * @property  boolean $adiantamentoDecimo
 * @property  boolean $adiantamentoFerias
 */
class Ferias extends \yii\db\ActiveRecord
{
    
    public $diferencaData;
    public $diferencaData2;
    public $anoSaida;
    public $nomeProfessor;
    public $nomeFuncionario;
    public $idUser;
    public $detalharTotalUsufruto;
    public $detalharRestoUsufruto;
    public $totalFeriasOficial;
    public $dias;
    public $nome;
    public $solicitacao;
	

    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_ferias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idusuario', 'nomeusuario', 'emailusuario', 'tipo', 'dataSaida', 'dataRetorno', 'adiantamentoDecimo', 'adiantamentoFerias'], 'required'],
            [['idusuario', 'tipo'], 'integer'],
            [['adiantamentoDecimo','adiantamentoFerias'], 'boolean'],
            [['dataSaida', 'dataRetorno', 'dataPedido','diferencaData2','diferencaData', 'nomeProfessor'], 'safe'],
            [['nomeusuario', 'emailusuario'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idusuario' => 'Id do Usuário',
            'nomeusuario' => 'Professor',
            'emailusuario' => 'Email do Usuário',
            'tipo' => 'Tipo Férias',
            'dataSaida' => 'Data Início',
            'dataRetorno' => 'Data Término',
			
			//***************Novos adicionados***************************************
			'adiantamentoDecimo' => 'Adiantamento de 50% do 13º',
			'adiantamentoFerias' => 'Antecipação de Férias',
			
			//************************************************************************
        ];
    }
    
    
    
    public function getDiferencaData(){
        
                $dataSaida = date('Y-m-d', strtotime($this->dataSaida));
                $dataRetorno =  date('Y-m-d', strtotime($this->dataRetorno));


                $datetime1 = new \DateTime($dataSaida);
                $datetime2 = new \DateTime($dataRetorno);
                $interval = $datetime1->diff($datetime2);
                $diferencaDias =  $interval->format('%a');
                return $diferencaDias;
    }

    public function pegarSolicitacao($idusuario,$ano){
        $registro = Ferias::find()->where(['idusuario' => $idusuario])->andWhere(['tipo' => 2])->andWhere(['YEAR(dataSaida)' => $ano, 'tipo' => 2])->all();
        $this->solicitacao = 0;
        foreach ($registro as $value){
            $this->solicitacao = $this->solicitacao + 1;
        };

        return $this->solicitacao;
    }

    public function verificarSolicitacao($idusuario,$ano){
        if($this->pegarSolicitacao($idusuario,$ano) < 3){
            return true;
        }else{
            return false;
        }
    }

    public function anosFerias($idusuario){
        
        if($idusuario == null){
            $anos_model = Ferias::find()->select("YEAR(dataSaida) as anoSaida")->all();
        }else{

            $anos_model = Ferias::find()->select("YEAR(dataSaida) as anoSaida")->where(["idusuario" => $idusuario])->all();
        }

        $anos = array (0 => date("Y"));
        
        for($i=0; $i<count($anos_model); $i++){
            $anos[$i] = $anos_model[$i]->anoSaida;
        }


        $anos = array_unique($anos);
       rsort($anos);


        return $anos;

    }


    public function feriasAno($idusuario,$ano,$tipo){

        $ferias = Ferias::find()->where(["idusuario" => $idusuario , "tipo" => $tipo ])->all();
        $cont = 0;
        $arrayDias = array();

        for($i = 0; $i < count($ferias) ; $i++ ){

            $anoSaida = date('Y', strtotime($ferias[$i]->dataSaida));

            if($anoSaida == $ano){
                $datetime1 = new \DateTime($ferias[$i]->dataSaida);
                $datetime2 = new \DateTime($ferias[$i]->dataRetorno);
                $interval = $datetime1->diff($datetime2);
                $arrayDias[$cont] =  abs($interval->format('%a'));
                $cont++;
            }

        }

        return array_sum($arrayDias) + $cont;

    }

    public function diasRestantesParaGozoDeFerias($idusuario){

        $sql="SELECT DATEDIFF(dataSaida,now()) AS dias FROM j17_ferias WHERE idusuario=:id and dataSaida>=now() ORDER BY dataSaida";
        $params=array(':id'=>$idusuario);
        $ferias = Ferias::findBySql($sql, $params);

        if($ferias->exists()){
            return $ferias->one()->dias;
        }else{
            return 0;
        }
        //return $ferias;

    }
    
    public function feriasAnoTodos($ano,$tipo){

        $ferias = Ferias::find()->where(["tipo" => $tipo ])->all();
        $cont = 0;
        $arrayDias = array();

        for($i = 0; $i < count($ferias) ; $i++ ){

            $anoSaida = date('Y', strtotime($ferias[$i]->dataSaida));

            if($anoSaida == $ano){
                $datetime1 = new \DateTime($ferias[$i]->dataSaida);
                $datetime2 = new \DateTime($ferias[$i]->dataRetorno);
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
