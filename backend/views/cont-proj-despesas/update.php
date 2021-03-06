<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjDespesas */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Despesas', 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cont-proj-despesas-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>


    <?= $this->render('_form', [
        'model' => $model,
        'idProjeto' => $idProjeto,
        'rubricasDeProjeto' => $rubricasDeProjeto,
        'update'=>true,
    ]) ?>

</div>
