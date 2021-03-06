<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */

//$nomeusuario = Ferias::find()->select("j17_ferias.nomeusuario")->from('j17_ferias')->where(['idusuario' => $_GET["id"]])->one()->nomeusuario;
$id = $_GET["id"];
$ehProf = User::find()->where(["id" => $model->idusuario])->one()->professor;


$this->title = 'Editar Frequencias';
$this->params['breadcrumbs'][] = ['label' => 'Frequências', 'url' => ['listartodos',  "ano" => $_GET["ano"],"mes" => $_GET["mes"]]];
$this->params['breadcrumbs'][] = ['label' => 'Detalhes Frequências', 'url' => ['detalhar', "id" => $model->idusuario, "ano" => $_GET["ano"],"mes" => $_GET["mes"], "prof" => $ehProf]];
$this->params['breadcrumbs'][] = ['label' => 'Editar Férias '.$model->nomeusuario];
?>
<div class="frequencias-update">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
    </p>
    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
