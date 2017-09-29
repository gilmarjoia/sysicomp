<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencias */

$this->title = 'Criar Ocorrencia';
$this->params['breadcrumbs'][] = ['label' => 'Ocorrencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ocorrencias-create">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Ocorrências', ['linha-pesquisa/index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
