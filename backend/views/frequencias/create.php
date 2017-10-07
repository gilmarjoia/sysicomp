<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */

$this->title = 'Registrar Frequências';
$this->params['breadcrumbs'][] = ['label' => 'Minhas Frequências', 'url' => ['index']];// , "ano" => $_GET["ano"]]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frequencias-create">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
    </p>



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
