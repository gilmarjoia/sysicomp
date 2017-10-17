<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\grid\CheckBoxColumn;
use xj\bootbox\BootboxAsset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FeriasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = 'Detalhes de Férias';

$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Férias', 'url' => ['listartodos',  "ano" => $_GET["ano"] ]];
$this->params['breadcrumbs'][] = $this->title;



if( isset($_GET["ano"]) && isset($_GET["prof"]) ){
    $anoVoltar = $_GET["ano"];
    $profVoltar = $_GET["prof"];
}

?>



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['listartodos', "ano" => $anoVoltar ], ['class' => 'btn btn-warning']) ?>  
        <?= Html::a('Registrar Novas Férias', ['createsecretaria' , "id" => $id, "ano" => $anoVoltar , "prof" => $profVoltar ], ['class' => 'btn btn-success']) ?>
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
			'value' => $profVoltar == 1 ? "Professor" : "Secretaria"
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

<?php
    $this->registerJS('$("#butt").click(function(){
                            var checked=$("#item-grid").yiiGridView("getSelectedRows"); 
                            var count=checked.length;
                            
                            if(count>0){
                                bootbox.confirm("Tem certeza que deseja deletar as férias selecionados?", function(confirmed) {
                                    if(confirmed) {
                                        $.ajax({
                                            data:{checked:checked},
                                            url:"'.Url::To(array('ferias/remove')).'",
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

<button class='btn btn-danger' id='butt'>Remover Férias</button>

</div>

<div class="ferias-index">
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
             ['attribute' => 'dataPedido',
             'value' => function ($model){
                        return date('d-m-Y', strtotime($model->dataPedido));
             },
             ],
            //'idusuario',
            
            [
            'attribute' => 'nomeusuario',
            'label' => "Nome",

            ]
            ,
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
			 
            [
                'attribute' => 'diferencaData',
                'label' => "Nº de Dias",
                'value' => function ($model){
                            return ($model->diferencaData + 1);
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
            "attribute" =>'tipo',
            "value" => function ($model){

            	if($model->tipo == 1){
            		return "Usufruto";
            	}
            	else{
            		return "Oficial";
            	}

            },

            ],

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
            ],
        ],
    ]); ?>,
</h5>
</div>
