<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */

$this->title = 'Editar Frequencias: '.$model->nomeusuario;
$this->params['breadcrumbs'][] = ['label' => 'Frequencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="frequencias-update">
	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Frequências', ['frequencias/index','ano' => date("Y")], ['class' => 'btn btn-success']) ?>
    </p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
