<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;
use app\models\Ferias;
use yii\bootstrap\Progress;
BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\FeriasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$var = new Ferias();
//var_dump($var->diasRestantesParaGozoDeFerias('595'));
$this->title = 'Solicitações de Férias';
$this->params['breadcrumbs'][] = $this->title;

?>

<script type="text/javascript">
        
        function anoSelecionado(){
            var x = document.getElementById("comboBoxAno").value;

            window.location="index.php?r=ferias/listartodos&ano="+x; 

        }

</script>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['site/index'], ['class' => 'btn btn-warning']) ?> 
        <?= Html::a('<span class="glyphicon glyphicon-list-alt"></span> Gerar Relatório', ['ferias/printvacationreport'], ['target' => '_blank', 'class' => 'btn btn-info'])?>
    </p>


<p>
    Selecione um ano: <select id= "comboBoxAno" onChange="anoSelecionado();" class="form-control" style="margin-bottom: 20px; width:10%;">
        <?php for($i=0; $i<count($todosAnosFerias); $i++){ 

            $valores = $todosAnosFerias[$i];

            ?>
            <option <?php if($valores == $_GET["ano"]){echo "SELECTED";} ?> > <?php echo $valores ?> </option>
        <?php } ?>
    </select>
</p>

<h3 style = "text-align: center; border: solid 1px; padding: 5px 5px 5px 5px; background-color: lightblue ; font-weight: bold ; margin: 20px 0px 20px 0px"> Solicitações de Férias de Servidores </h3>

<div class="ferias-index">
<h5 style="background-color: lightblue">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        //'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             /*['attribute' => 'dataPedido',
             'value' => function ($model){
                        return date('d-m-Y', strtotime($model->dataPedido));
             },
             ],
             */

            //'nomeusuario',
            [
            'attribute' => 'nome',
            'contentOptions'=>['style'=>'max-width: 0px;'],
            ]
            ,
            [
                'label' => 'Férias Oficiais' ,
                 'value' => function ($model){
                            return $model->feriasAno($model->id, $_GET["ano"] , 2 );
                 },
            ],
            [
                'label' => 'Usufruto de Férias' ,
                 'value' => function ($model){
                            return $model->feriasAno($model->id, $_GET["ano"] , 1 );
                 },
            ],
            [
                 'label' => 'Dias restantes para usufruto' ,
                 'content' => function($model) {
                     if ($model->diasRestantesParaGozoDeFerias($model->id)<=20){
                         return Progress::widget([
                             'label' => $model->diasRestantesParaGozoDeFerias($model->id),
                             'percent' => $model->diasRestantesParaGozoDeFerias($model->id),
                             'barOptions' => ['class' => 'progress-bar-danger'],
                             'options' => ['class' => 'active progress-striped']
                         ]);
                     }elseif($model->diasRestantesParaGozoDeFerias($model->id)>20 and $model->diasRestantesParaGozoDeFerias($model->id)<=30){
                         return Progress::widget([
                             'label' => $model->diasRestantesParaGozoDeFerias($model->id),
                             'percent' => $model->diasRestantesParaGozoDeFerias($model->id),
                             'barOptions' => ['class' => 'progress-bar-warning'],
                             'options' => ['class' => 'active progress-striped']
                         ]);
                     }else{
                         return Progress::widget([
                             'label' => $model->diasRestantesParaGozoDeFerias($model->id),
                             'percent' => $model->diasRestantesParaGozoDeFerias($model->id),
                             'barOptions' => ['class' => 'progress-bar-info'],
                             'options' => ['class' => 'active progress-striped']
                         ]);
                     }
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{view}',
                'buttons'=>[
                  'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['detalhar', 
                        'id' => $model->id , 'ano' => $_GET["ano"], "prof" => 1], [
                            'title' => Yii::t('yii', 'Visualizar Detalhes'),
                    ]);   
                  }
                ]                            
            ],
        ],
    ]); ?>
</h5>

<!--  DAQUI PARA BAIXO O CODIGO ESTA TODO COMENTADO, PORQUE ESTA PARTE NAO EH MAIS NECESSARIA

<h3 style = "text-align: center; border: solid 1px; padding: 5px 5px 5px 5px; background-color: lightblue ; font-weight: bold; margin: 20px 0px 20px 0px"> Solicitações de Férias de Funcionários </h3>
<h5 style="background-color: lightblue">
 <?= GridView::widget([
        'dataProvider' => $dataProvider2,

        //'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             /*['attribute' => 'dataPedido',
             'value' => function ($model){
                        return date('d-m-Y', strtotime($model->dataPedido));
             },
             ],
             */

            //'nomeusuario',
            [
            'attribute' => 'nome',
            'contentOptions'=>['style'=>'max-width: 0px;'],
            ]
            ,
           [
                'label' => 'Férias Oficiais' ,
                 'value' => function ($model){
                            return $model->feriasAno($model->id, $_GET["ano"] , 2 );
                 },
            ],
            [
                'label' => 'Usufruto de Férias' ,
                 'value' => function ($model){
                            return $model->feriasAno($model->id, $_GET["ano"] , 1 );
                 },
            ],
            [
                'label' => 'Dias restantes para usufruto' ,
                'content' => function($model) {
                    if ($model->diasRestantesParaGozoDeFerias($model->id)<=20){
                        return Progress::widget([
                            'label' => $model->diasRestantesParaGozoDeFerias($model->id),
                            'percent' => $model->diasRestantesParaGozoDeFerias($model->id),
                            'barOptions' => ['class' => 'progress-bar-danger'],
                            'options' => ['class' => 'active progress-striped']
                        ]);
                    }elseif($model->diasRestantesParaGozoDeFerias($model->id)>20 and $model->diasRestantesParaGozoDeFerias($model->id)<=30){
                        return Progress::widget([
                            'label' => $model->diasRestantesParaGozoDeFerias($model->id),
                            'percent' => $model->diasRestantesParaGozoDeFerias($model->id),
                            'barOptions' => ['class' => 'progress-bar-warning'],
                            'options' => ['class' => 'active progress-striped']
                        ]);
                    }else{
                        return Progress::widget([
                            'label' => $model->diasRestantesParaGozoDeFerias($model->id),
                            'percent' => $model->diasRestantesParaGozoDeFerias($model->id),
                            'barOptions' => ['class' => 'progress-bar-info'],
                            'options' => ['class' => 'active progress-striped']
                        ]);
                    }
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{view}',
                'buttons'=>[
                  'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['detalhar', 
                        'id' => $model->id , 'ano' => $_GET["ano"], "prof" => 0], [
                        'title' => Yii::t('yii', 'Visualizar Detalhes'),
                    ]);   
                  }
              ]                            
                ],
        ],
    ]); ?>
</h5>

-->
</div>
