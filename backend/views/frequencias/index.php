<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FrequenciasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Frequencias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frequencias-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Frequencias', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idusuario',
            'nomeusuario',
            'dataInicial',
            'dataFinal',
            // 'codigoOcorrencia',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
