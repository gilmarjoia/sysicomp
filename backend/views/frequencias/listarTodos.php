<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;
use app\models\Frequencias;
use app\models\user;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$var = new Ferias();
//var_dump($var->diasRestantesParaGozoDeFerias('595'));
$this->title = 'Frequências';
$this->params['breadcrumbs'][] = $this->title;

?>

<script type="text/javascript">

    function anoSelecionado(){
        var x = document.getElementById("comboBoxAno").value;
        var y = document.getElementById("comboBoxMes").value;

        window.location="index.php?r=frequencias/listartodos&ano="+x+"&mes="+y;

    }

</script>


<p>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['site/index'], ['class' => 'btn btn-warning']) ?>
    <?= Html::a('<span class="glyphicon glyphicon-list-alt"></span> Gerar Relatório', ['Frequencias/printreport', "ano" => $_GET["ano"], "mes" => $_GET["mes"]] , ['target' => '_blank', 'class' => 'btn btn-info'])?>
</p>
</p>


<p>
    Selecione um mês: <select id="comboBoxMes" onchange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:10%;">
        <?php for($i=0; $i<count($todosMesFrequencias); $i++){

            $valores = $todosMesFrequencias[$i];

            ?>
            <option <?php if($valores == $_GET["mes"]){echo "SELECTED";} ?> > <?php echo $valores?> </option>
        <?php } ?>
    </select>
    Selecione um ano: <select id= "comboBoxAno" onChange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:10%;">
        <?php for($i=0; $i<count($todosAnosFrequencias); $i++){

            $valores = $todosAnosFrequencias[$i];

            ?>
            <option <?php if($valores == $_GET["ano"]){echo "SELECTED";} ?> > <?php echo $valores?> </option>
        <?php } ?>
    </select>

</p>

<h3 style = "text-align: center; border: solid 1px; padding: 5px 5px 5px 5px; background-color: lightblue ; font-weight: bold ; margin: 20px 0px 20px 0px"> Frequências dos Servidores </h3>

<div class="frequencias-index">

   <h5 style="background-color: lightblue">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,

            'filterModel' => $searchModel,

            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                /*['attribute' => 'dataPedido',
                'value' => function ($model){
                           return date('d-m-Y', strtotime($model->dataPedido));
                },
                ],
                */

                [
                    'label' => 'Matrícula SIAPE' ,
                    'value' => function ($model){
                       return User::find()->select("j17_user.siape, j17_user.id")->where(["j17_user.id" => $model->id])->one()->siape;
                    },
                ],

                //'nomeusuario',
                [
                    'attribute' => 'nome',
                    //'contentOptions'=>['style'=>'max-width: 0px;'],
                ],
	    	    [
                    'label' => 'Cargo',
                    'value' => function ($model){
                        return User::find()->select("j17_user.cargo, j17_user.id")->where(["j17_user.id" => $model->id])->one()->cargo;
                    },
                ],

                [
                    'label' => 'Quantidade de Dias a Pagar ' ,
                    'value' => function ($model){
                        return $model->contarDiasPagar($model->id, $_GET["ano"], $_GET["mes"]);
                    },
                ],
                [
                    'label' => 'Total de Ocorrências' ,
                    'value' => function ($model){
                        return $model->contarOcorrencias($model->id,$_GET["ano"], $_GET["mes"]);
                    },
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view}',
                    'buttons'=>[
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['detalhar',
                                'id' => $model->id , 'ano' => $_GET["ano"],'mes' =>$_GET["mes"], "prof" => 1], [
                                'title' => Yii::t('yii', 'Visualizar Detalhes'),
                            ]);
                        }
                    ]
                ],
            ],
        ]); ?>
    </h5>

</div>
