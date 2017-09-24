<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ferias */

$this->title = 'Editar Férias';
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Férias', 'url' => ['listartodos',  "ano" => $_GET["ano"]]];
$this->params['breadcrumbs'][] = ['label' => 'Detalhes Férias', 'url' => ['detalhar', "id" => $model->idusuario, "ano" => $_GET["ano"], "prof" => $model->tipo]];
$this->params['breadcrumbs'][] = ['label' => 'Editar Férias '.$model->nomeusuario];

?>
<div class="ferias-update">

<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar ', ['detalhar', "id" => $model->idusuario, "ano" => $_GET["ano"], "prof" => $model->tipo], ['class' => 'btn btn-warning']) ?>
</p>

    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
