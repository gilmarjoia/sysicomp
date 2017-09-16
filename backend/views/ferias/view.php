<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ferias */

$this->title = "Detalhes - Férias";
$this->params['breadcrumbs'][] = ['label' => 'Ferias', 'url' => ['listar', "ano" => date("Y")]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ferias-view">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-remove-sign"></span> Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'label' => 'Data Pedido',
                'attribute' => 'dataPedido',
                'value' => date("d-m-Y", strtotime($model->dataPedido)),

            ],
            'nomeusuario',
            'emailusuario:email',
            [
                'attribute' => 'tipo',
                'format'=>'raw',
                'value' => $model->tipo == 1 ? 'Usufruto' : 'Oficial',
            ],
            [
                'label' => 'Data Início',
                'attribute' => 'dataSaida',
                'value' => date("d-m-Y", strtotime($model->dataSaida)),

            ],
			
            [
                'label' => 'Data Término',
                'attribute' => 'dataRetorno',
                'value' => date("d-m-Y", strtotime($model->dataRetorno)),

            ],
        ],
    ]) ?>

</div>
