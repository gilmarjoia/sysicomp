<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */

$this->title = 'Criar Frequencias';
$this->params['breadcrumbs'][] = ['label' => 'Frequencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frequencias-create">

	
	<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
    <?= Html::a('<span class="fa fa-list"></span>&nbsp;&nbsp;Listar Ocorrências', ['linha-pesquisa/index'], ['class' => 'btn btn-success']) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
