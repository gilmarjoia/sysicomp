<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencias */

$this->title = 'Ocorrência: '.$model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencias-view">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Ocorrencias', ['ocorrencias/index','ano' => date("Y")], ['class' => 'btn btn-success']) ?>    
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-remove"></span> Remover', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Remover Ocorrência \''. $model->codigo.'\'?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Dados da Ocorrência</b></h3>
            </div>
            <div class="panel-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                         'label' => 'Codigo',
                         'value' => function($model){
                                        return $model->getCodigo();
                                    }    
                        ],
                        'ocorrencia:ntext',
                    ],
                ]) ?>
            </div>
        </div>
    </div>    

</div>
