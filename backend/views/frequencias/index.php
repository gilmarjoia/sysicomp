<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = 'Minhas Frequências';
$this->params['breadcrumbs'][] = $this->title;


?>

<script type="text/javascript">

    function anoSelecionado(){
        var x = document.getElementById("comboBoxAno").value;

        window.location="index.php?r=frequencias/listar&ano="+x;

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


<div class="frequencias-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        Selecione um ano: <select id= "comboBoxAno" onchange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:10%;">
            <?php for($i=0; $i<count($todosAnosFrequencias); $i++){

                $valores = $todosAnosFrequencias[$i];

                ?>
                <option <?php if($valores == $_GET["ano"]){echo "SELECTED";} ?> > <?php echo $valores ?> </option>
            <?php } ?>
        </select>
    </p>
    <h5 style="background-color: lightblue">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                //'idusuario',
                'nomeusuario',
                'dataInicial',
                'dataFinal',
                // 'codigoOcorrencia',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
                    'buttons'=>[
                        'update' => function ($url, $model) {
                            if (Yii::$app->user->identity->secretaria){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id , "ano" => $_GET["ano"]], ['title' => Yii::t('yii', 'Editar Frequências'),
                                ]);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (Yii::$app->user->identity->secretaria){
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete', 'id' => $model->id, 'idUsuario' => $model->idusuario , 'ano'=>$_GET['ano']   ,], [
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
