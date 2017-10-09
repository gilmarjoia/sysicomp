<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use xj\bootbox\BootboxAsset;
use app\models\Ocorrencias;

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

<?= DetailView::widget([
    'model' => $model_do_usuario,
    'attributes' => [


        [
            'attribute' => 'nome',
            'label' => 'Nome',
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
                return $model->contarDiasPagar($model->id,$_GET["ano"]);
            },

        ],
        [
            'attribute' => 'totalOcorrencia',
            'label' => 'Número de Ocorrências',
            'value'=> function ($model){
                return $model->contarOcorrencias($model->id);
            },
        ],
    ],
]) ?>


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
                'codigoOcorrencia',

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
