<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\grid\CheckBoxColumn;
use xj\bootbox\BootboxAsset;
use yii\helpers\Url;
use yii\web\View;
use app\models\Ocorrencias;
use app\models\User;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = 'Minhas Frequências';
$this->params['breadcrumbs'][] = $this->title;

//$ocorrencia = Ocorrencias::find()->select('j17_ocorrencias.ocorrencia')->from('j17_ocorrencias')->column();
//print_r($ocorrencia);

?>

<script type="text/javascript">

    function anoSelecionado(){
        var x = document.getElementById("comboBoxAno").value;
        var y = document.getElementById("comboBoxMes").value;

        window.location="index.php?r=frequencias/listar&ano="+x+"&mes="+y;

    }

</script>



<p>
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['site/index'], ['class' => 'btn btn-warning']) ?>
    <?php
        if(Yii::$app->user->identity->secretaria){
            echo Html::a('Registrar Frequências', ['create', "ano" => $_GET["ano"]], ['class' => 'btn btn-success']);
        }
    ?>
</p>

<?= DetailView::widget([
    'model' => $model_do_usuario,
    'attributes' => [


        [
            'attribute' => 'nome',
            'label' => 'Nome',
        ],
        [
            'label' => 'Matrícula SIAPE' ,
            'value' => function ($model){
                return User::find()->select("j17_user.siape, j17_user.id")->where(["j17_user.id" => $model->id])->one()->siape;
            },
        ],
        [
            'label' => 'Cargo',
            'value' => function ($model){
                return User::find()->select("j17_user.cargo, j17_user.id")->where(["j17_user.id" => $model->id])->one()->cargo;
            },
        ],

        //[
         //   'attribute' => 'categoria',
           // 'label' => 'Categoria',
            //'value' => $profVoltar != 0 ? "Professor" : "Secretaria"
        //],

        [
            'attribute' => 'diasPagar',
            'label' => 'Quantidade de Dias a Pagar:',
            'value'=> function ($model){
                return $model->contarDiasPagar($model->id,$_GET["ano"],$_GET["mes"]);
            },

        ],
        [
            'attribute' => 'totalOcorrencia',
            'label' => 'Número de Ocorrências',
            'value'=> function ($model){
                return $model->contarOcorrencias($model->id,$_GET["ano"],$_GET["mes"]);
            },
        ],
    ],
]) ?>

<p>
    Selecione um mes: <select id="comboBoxMes" onchange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:10%;">
        <?php for($i=0; $i<count($todosMesFrequencias); $i++){

            $valores = $todosMesFrequencias[$i];

            ?>
            <option <?php if($valores == $_GET["mes"]){echo "SELECTED";} ?> > <?php echo $valores ?> </option>
        <?php } ?>
    </select>
    Selecione um ano: <select id= "comboBoxAno" onchange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:10%;">
        <?php for($i=0; $i<count($todosAnosFrequencias); $i++){

            $valores = $todosAnosFrequencias[$i];

            ?>
            <option <?php if($valores == $_GET["ano"]){echo "SELECTED";} ?> > <?php echo $valores ?> </option>
        <?php } ?>
    </select>
</p>

<?php
    $this->registerJS('$("#butt").click(function(){
                            var checked=$("#item-grid").yiiGridView("getSelectedRows"); 
                            var count=checked.length;
                            
                            if(count>0){
                                bootbox.confirm("Tem certeza que deseja deletar as férias selecionados?", function(confirmed) {
                                    if(confirmed) {
                                        $.ajax({
                                            data:{checked:checked},
                                            url:"'.Url::To(array('frequencias/remove')).'",
                                            success:function(data){$("#item-grid").yiiGridView("applyFilter");},              
                                        });
                                    }
                                });                         
                            }
                        });

                        $(".chkGrid").change(function(){
                            var checked=$("#item-grid").yiiGridView("getSelectedRows");
                            var count=checked.length;
                            if (count>0){
                                $("#RemoverVarios").show();
                            }else {
                                $("#RemoverVarios").hide();
                            }
                        });

                        $(".select-on-check-all").change(function(){
                            var checked=$("#item-grid").yiiGridView("getSelectedRows");
                            var count=checked.length;
                            if (count>0){
                                $("#RemoverVarios").show();
                            }else {
                                $("#RemoverVarios").hide();
                            }
                        });

                        document.getElementById("RemoverVarios").style.display = "none";

                        ',View::POS_READY,'my-button-handler');
?>


<div id='RemoverVarios'> 

<button class='btn btn-danger' id='butt'>Remover Frequências</button>

</div>


<div class="frequencias-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <h5 style="background-color: lightblue">
        <?= GridView::widget([
   	        'id' => 'item-grid',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                [
	             'class' => 'yii\grid\CheckboxColumn',
	             'checkboxOptions' => function($model, $key, $index, $column){
	                                        return ['class' => 'chkGrid'];
	                                  	}, 
	            ],

                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                //'idusuario',
                //'nomeusuario',
                'dataInicial',
                'dataFinal',
                [
                 'label' => 'Código da Ocorrência',
                 'value' => function($model){
                                return Ocorrencias::find()->where(["codigo" => $model->codigoOcorrencia])->one()->getCodigo();
                            }    
                ],
                [
                    'label' => 'Descrição',
                    'content' => function($model){
                        //print_r($model->codigoOcorrencia);
                        return Ocorrencias::find()->select('j17_ocorrencias.ocorrencia')->from('j17_ocorrencias')->where(['codigo' => $model->pegarCodigoOcorrencia($model->codigoOcorrencia)])->one()->ocorrencia;
                    },
                ],



                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
                    'buttons'=>[
                        'update' => function ($url, $model) {
                            if (Yii::$app->user->identity->secretaria){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id, "ano" => $_GET["ano"], "mes" => $_GET["mes"]], ['title' => Yii::t('yii', 'Editar Frequências'),
                                ]);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (Yii::$app->user->identity->secretaria){
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete', 'id' => $model->id, 'idUsuario' => $model->idusuario , 'ano'=>$_GET['ano'],'mes' => $_GET["mes"] ,], [
                                    'data' => [
                                        'confirm' => "Você realmente deseja excluir o registro de frequência?",
                                        'method' => 'post',
                                    ],

                                    'title' => Yii::t('yii', 'Remover Frequência'),
                                ]);
                            }

                        }
                    ]
                ],
            ],
        ]); ?>
    </h5>

</div>
