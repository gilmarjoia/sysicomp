<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FrequenciasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalhes de Frequências';

$this->params['breadcrumbs'][] = ['label' => 'Frequências', 'url' => ['listartodos',  "ano" => $_GET["ano"] ]];
$this->params['breadcrumbs'][] = $this->title;



if( isset($_GET["ano"]) && isset($_GET["prof"]) ){
    $anoVoltar = $_GET["ano"];
    $profVoltar = $_GET["prof"];
}

?>



<?php  //echo $this->render('_search', ['model' => $searchModel]); ?>


<p>

        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['listartodos', "ano" => $anoVoltar ], ['class' => 'btn btn-warning']) ?>  
        
    </p>

<?= DetailView::widget([
    'model' => $model_do_usuario,
    'attributes' => [


        [
            'attribute' => 'nome',
            'label' => 'Nome',
        ],

        [
            'attribute' => 'categoria',
            'label' => 'Categoria',
            'value' => $profVoltar == 0 ? "Professor" : "Secretaria"
        ],

        [
            'attribute' => 'diasPagar',
            'label' => 'Quantidade de Dias a Pagar:',
            'value'=> 1,

        ],
        [
            'attribute' => 'totalOcorrencia',
            'label' => 'Número de Ocorrências',
            'value'=> 2,
        ],
    ],
]) ?>

<div class="frequencias-index">
    <h5 style="background-color: lightblue">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'idusuario',

                [
                    'attribute' => 'nomeusuario',
                    'label' => "Nome",

                ]
                ,
                ['attribute' => 'dataInicial',
                    'value' => function ($model){
                        return date('d-m-Y', strtotime($model->dataInicial));
                    },
                ],
                ['attribute' => 'dataFinal',
                    'value' => function ($model){
                        return date('d-m-Y', strtotime($model->dataFinal));
                    },
                ],
                /*
                /////////////////////////////////////////////Somente para secretaria//////////////////////////////////////////////
                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
                    'buttons'=>[
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id , "ano" => $_GET["ano"]], [
                                'title' => Yii::t('yii', 'Editar Férias'),
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['deletesecretaria', 'id' => $model->id, 'idUsuario' => $model->idusuario , 'ano'=>$_GET['ano'], 'prof' => $_GET["prof"],], [

                                'data' => [
                                    'confirm' => "Você realmente deseja excluir o registro dessas férias?",
                                    'method' => 'post',
                                ],

                                'title' => Yii::t('yii', 'Remover Férias'),
                            ]);
                        }
                    ]
                ],*/
            ],
        ]); ?>
		
		
    </h5>
</div>