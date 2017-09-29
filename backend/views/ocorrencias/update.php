<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencias */

$this->title = 'Editar Ocorrencia: ' . $model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ocorrencias-update">
	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Ocorrências', ['ocorrencias/index'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
