<?php

use yii\helpers\Html;
use app\models\User;


/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */

if( isset($_GET["ano"]) && isset($_GET["prof"]) && isset($_GET["id"]) ){
    $anoVoltar = $_GET["ano"];
    $profVoltar = $_GET["prof"];
    $idVoltar = $_GET["id"];
    $model_do_usuario = User::find()->where(["id" => $idVoltar])->one();
}


$this->title = 'Registrar Frequências';
$this->params['breadcrumbs'][] = ['label' => 'Frequências', 'url' => ['listartodos' , "ano" => $_GET["ano"]]];
$this->params['breadcrumbs'][] = ['label' => 'Detalhes de Frequências', 'url' => ['detalhar' , "id" => $idVoltar , "ano" => $anoVoltar , "prof" => $profVoltar ]];
$this->params['breadcrumbs'][] = $this->title.' '.$model_do_usuario->nome;



?>
<div class="frequencias-create">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['detalhar', "id" => $idVoltar , "ano" => $anoVoltar , "prof" => $profVoltar  ], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
