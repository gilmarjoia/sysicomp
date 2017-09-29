<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FrequenciasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="frequencias-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idusuario') ?>

    <?= $form->field($model, 'nomeusuario') ?>

    <?= $form->field($model, 'dataInicial') ?>

    <?= $form->field($model, 'dataFinal') ?>

    <?php // echo $form->field($model, 'codigoOcorrencia') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
