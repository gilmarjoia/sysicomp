<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = 'Minhas Solicitações de Férias';
$this->params['breadcrumbs'][] = $this->title;

?>

<script type="text/javascript">
        
        function anoSelecionado(){
            var x = document.getElementById("comboBoxAno").value;

            window.location="index.php?r=ferias/listar&ano="+x; 

        }

</script>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['site/index'], ['class' => 'btn btn-warning']) ?>   
        <?= Html::a('Registrar Novas Férias', ['create', "ano" => $_GET["ano"]], ['class' => 'btn btn-success']) ?>
    </p>



<div class="ferias-index">

    <?= DetailView::widget([
        'model' => $model_do_usuario,
        'attributes' => [


            [
            'attribute' => 'nome',
            'label' => 'Nome',
            ],

            [
            'attribute' => 'totalFeriasOficial',
            'label' => 'Total de dias de férias oficiais:',
            'value'=> $qtd_ferias_oficiais,

            ],
            [
            'attribute' => 'detalharTotalUsufruto',
            'label' => 'Total de dias de usufruto de férias:',
            'value'=> $qtd_usufruto_ferias,
            ],
            [
            'attribute' => 'detalharRestoUsufruto',
            'label' => 'Dias restantes de usufruto de férias:',
            'value'=> ($direitoQtdFerias-$qtd_usufruto_ferias),
            ],

        ],
    ]) ?>

<p>
    Selecione um ano: <select id= "comboBoxAno" onchange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:10%;">
        <?php for($i=0; $i<count($todosAnosFerias); $i++){ 

            $valores = $todosAnosFerias[$i];

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
             ['attribute' => 'dataPedido',
             'value' => function ($model){
                        return date('d/m/Y', strtotime($model->dataPedido));
             },
             ],
            //'idusuario',
            //'nomeusuario',
             ['attribute' => 'dataSaida',
             'value' => function ($model){
                        return date('d/m/Y', strtotime($model->dataSaida));
             },
             ],
             ['attribute' => 'dataRetorno',
             'value' => function ($model){
                        return date('d/m/Y', strtotime($model->dataRetorno));
             },
             ],

            /********************ADIANTAMENTO DECIMO E FERIAS********************/
            [
                'attribute' => 'adiantamentoDecimo',
                'label' => "Adiantamento de 50% do 13º",
                'value' => function ($model){
                    if($model->adiantamentoDecimo == 1){
                        return "Sim";
                    }
                    else{
                        return "Não";
                    }
                },
            ],
            [
                'attribute' => 'adiantamentoFerias',
                'label' => "Antecipação de Férias",
                'value' => function ($model){
                    if($model->adiantamentoFerias == 1){
                        return "Sim";
                    }
                    else{
                        return "Não";
                    }
                },
            ],

            /********************************************************************/


            [
                 'attribute' => 'diferencaData',
                 'label' => "Nº de Dias",
                 'value' => function ($model){
                            return ($model->diferencaData + 1);
                 },
            ],
                     
            [
                "attribute" =>'tipo',
                "value" => function ($model){
                     if($model->tipo == 1){
                         return "Usufruto";
                     }else{
                         return "Oficial";
                     }
                 },
            ],

            /////////////////////////////Ediçao e delete somente para secretaria///////////////////////////////////////////////////////////

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
                'buttons'=>[
                        'update' => function ($url, $model) {
                            if (Yii::$app->user->identity->secretaria){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id , "ano" => $_GET["ano"]], ['title' => Yii::t('yii', 'Editar Férias'),
                                ]);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (Yii::$app->user->identity->secretaria){
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete', 'id' => $model->id, 'idUsuario' => $model->idusuario , 'ano'=>$_GET['ano']   ,], [
                                    'data' => [
                                        'confirm' => "Você realmente deseja excluir o registro dessas férias?",
                                        'method' => 'post',
                                    ],

                                    'title' => Yii::t('yii', 'Remover Férias'),
                                ]);
                            }

                        }
                ]
            ],
        ],
    ]); ?>
</h5>
</div>
