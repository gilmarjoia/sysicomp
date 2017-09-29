<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */

$this->title = 'Create Frequencias';
$this->params['breadcrumbs'][] = ['label' => 'Frequencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frequencias-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
