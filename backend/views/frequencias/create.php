<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */

$this->title = 'Registrar Frequências';
$this->params['breadcrumbs'][] = ['label' => 'Minhas Frequências', 'url' => ['listar' , "ano" => $_GET["ano"]]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frequencias-create">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['listar', "ano" => $_GET["ano"], ], ['class' => 'btn btn-warning']) ?>
    </p>


    <?= $this->render('_formMinhasFrequencias', [
        'model' => $model,
    ]) ?>

</div>
