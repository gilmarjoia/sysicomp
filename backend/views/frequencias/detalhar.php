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

$this->title = 'Detalhes de Frequências';

$this->params['breadcrumbs'][] = ['label' => 'Frequências', 'url' => ['listartodos',  "ano" => $_GET["ano"],"mes"=>$_GET["mes"] ]];
$this->params['breadcrumbs'][] = $this->title;

if( isset($_GET["ano"]) && isset($_GET["mes"]) && isset($_GET["prof"]) ){
    $anoVoltar = $_GET["ano"];
    $mesVoltar = $_GET["mes"];
    $profVoltar = $_GET["prof"];

}

?>

<script type="text/javascript">

    function anoSelecionado(){
        var x = document.getElementById("comboBoxAno").value;
        var y = document.getElementById("comboBoxMes").value;
        var id = <?= $id ?>;
        var prof = <?= $profVoltar ?>;

        window.location="index.php?r=frequencias/detalhar&ano="+x+"&mes="+y+"&id="+id+"&prof="+prof;

    }

</script>



<?php  //echo $this->render('_search', ['model' => $searchModel]); ?>


    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['listartodos', "ano" => $anoVoltar, "mes" => $mesVoltar ], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Registrar Novas Frequências', ['createsecretaria' , "id" => $id, "ano" => $anoVoltar ,"mes" => $mesVoltar, "prof" => $profVoltar ], ['class' => 'btn btn-success']) ?>
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

        [
            'attribute' => 'categoria',
            'label' => 'Categoria',
            'value' => $profVoltar != 0 ? "Professor" : "Secretaria"
        ],

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
                
                ['attribute' => 'dataInicial',
                    'value' => function ($model){
                        return date('d/m/Y', strtotime($model->dataInicial));
                    },
                ],
                ['attribute' => 'dataFinal',
                    'value' => function ($model){
                        return date('d/m/Y', strtotime($model->dataFinal));
                    },
                ],

                [
                 'label' => 'Código da Ocorrência',
                 'value' => function($model){
                                return Ocorrencias::find()->where(["codigo" => $model->codigoOcorrencia])->one()->getCodigo();
                            }    
                ],


                [
                    'label' => 'Descrição da Ocorrência',
                    'content' => function($model){
                        return Ocorrencias::find()->where(['codigo' => $model->codigoOcorrencia])->one()->ocorrencia;
                    },
                ],

                /////////////////////////////////////////////Somente para secretaria//////////////////////////////////////////////
                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete} {copy}',
                    'buttons'=>[
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id, "ano" => $_GET["ano"],"mes" => $_GET['mes']], [
                                'title' => Yii::t('yii', 'Editar Frequência'),
                            ]);    
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['deletesecretaria', 'id' => $model->id, 'idUsuario' => $model->idusuario , 'ano'=>$_GET["ano"], 'mes' => $_GET["mes"], 'prof' => $_GET["prof"],], [


                                'data' => [
                                    'confirm' => "Você realmente deseja excluir o registro dessa Frequência?",
                                    'method' => 'post',
                                ],

                                'title' => Yii::t('yii', 'Remover Frequência'),
                            ]);
                        },
                        'copy' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['replicarsecretaria', 'id' => $model->id, 'idUsuario' => $model->idusuario , 'ano'=>$_GET['ano'],'mes' => $_GET["mes"], 'prof' => $_GET["prof"],], [


                                'data' => [
                                    'confirm' => "Você realmente deseja replicar o registro dessa Frequência? ",

                                    'method' => 'post',
                                ],

                                'title' => Yii::t('yii', 'Replicar Frequência'),
                            ]);
                        }
                    ]
                ],
            ],
        ]); ?>


    </h5>
</div>
