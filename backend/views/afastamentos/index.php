<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use xj\bootbox\BootboxAsset;
use yii\bootstrap\Collapse;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);


/* @var $this yii\web\View */
/* @var $searchModel app\models\AfastamentosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedidos de Afastamento';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="afastamentos-index">

<?= Html::a('Novo Pedido de Afastamento', ['create'], ['class' => 'btn btn-success']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
        [   'label' => 'Data de Envio',
        //      'filter' => \yii\jui\DatePicker::widget(['language' => 'pt', 'dateFormat' => 'dd-MM-yyyy']),
                'attribute' => 'dataenvio',
                'value' => function ($model) {
                     return date("d/m/Y", strtotime($model->dataenvio));
                },
            ],
        'nomeusuario',
            [   'label' => 'Data de Saída',
                'attribute' => 'datasaida',
        //      'filter' => \yii\jui\DatePicker::widget(['language' => 'pt', 'dateFormat' => 'dd-MM-yyyy']),
                'value' => function ($model) {
                     return date("d/m/Y", strtotime($model->datasaida));
                },
            ],
            [   'label' => 'Data de Retorno',
                'attribute' => 'dataretorno',
                'format' => 'raw',
        //      'filter' => \yii\jui\DatePicker::widget(['language' => 'pt', 'dateFormat' => 'dd-MM-yyyy']),
                'value' => function ($model) {
                     return date("d/m/Y", strtotime($model->dataretorno));
                },
            ],
            [   'label' => 'Tipo de Viagem',
                'attribute' => 'tipo',
                'filter'=>array("1"=>"Nacional","2"=>"Internacional"),
                'value' => function ($model) {
                    return $model->tipo == 1 ? 'Nacional' : 'Internacional';
                },
            ],
             'local',
            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{print} {view} {delete}',
                'buttons'=>[
                  'print' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-print"></span>', ['print', 'id' => $model->id], [
                            'target' => '_blank','title' => Yii::t('yii', 'Imprimir Solcitação de Afastamento'),
                    ]);
                  },

                  'view' => function ($url, $model) {

                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id], [
                            'title' => Yii::t('yii', 'Visualizar Detalhes'),
                    ]);

                  },
                  'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => 'Remover o pedido de asfastamento \''.$model->id.'\' de '.$model->nomeusuario.'?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Afastamento'),
                    ]);
                  }
              ]
            ],
        ],
    ]); ?>

</div>
