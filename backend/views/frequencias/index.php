<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FrequenciasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Frequências';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frequencias-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nova Frequência', ['create'], ['class' => 'btn btn-success']) ?>
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
