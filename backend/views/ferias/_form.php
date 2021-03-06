<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use app\models\Ferias;


/* @var $this yii\web\View */
/* @var $model app\models\Ferias */
/* @var $form yii\widgets\ActiveForm */

//$dataRegistro = Ferias::find()->where(['idusuario' => $model->idusuario,'tipo' => 2])->all();
//$registro = Ferias::find()->where(['idusuario' => $model->idusuario])->andWhere(['tipo' => 2])->one();
//var_dump($registro);
//var_dump($dataRegistro);
//var_dump($model->pegarSolicitacao(72, $_GET['ano']));

$arrayTipoferias = ["1" => "Usufruto", "2" => "Oficial"];
?>

<div class="ferias-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class = "row">
        <?php if ($model->isNewRecord) echo $form->field($model, 'tipo' , ['options' => ['class' => 'col-md-3']])->dropDownlist($arrayTipoferias, ['options' => ['id' => 'input_tipo'],'prompt' => 'Selecione um tipo de Férias'])->label("<font color='#FF0000'>*</font> <b>Tipo:</b>");?>
    </div>

    <!--Não mudar a forma da data de dd-mm-yyyy para dd/mm/yyyy, ocorre bug na hora de pegar ano atual -->

    <div class = "row">
	        <?= $form->field($model, 'dataSaida', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
	                'language' => Yii::$app->language,
	                'options' => ['placeholder' => 'Selecione a Data de Saída ...',],
				    'pluginOptions' => [
				        'format' => 'dd-mm-yyyy',
				        'todayHighlight' => true
				    ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data Início:</b>")
		    ?>
	</div>

	<div class = "row">
		    
	        <?= $form->field($model, 'dataRetorno', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
	                'language' => Yii::$app->language,
	                'options' => ['placeholder' => 'Selecione a Data de Retorno ...',],
				    'pluginOptions' => [
				        'format' => 'dd-mm-yyyy',
				        'todayHighlight' => true
				    ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data Término:</b>")
		    ?>
    </div>

    <!------------------------- Novos adicionados-------------------------------------->

    <div class="row">
        <?= $form->field($model, 'adiantamentoDecimo', ['options' => ['class' => 'col-md-3']])->widget(SwitchInput::className(), [
            'type' => SwitchInput::CHECKBOX,
            'options' => ['id' => 'adiantamento_decimo'],
            'pluginOptions' => [
                'onText' => 'Sim',
                'offText' => 'Não',
            ],
        ]);
        ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'adiantamentoFerias', ['options' => ['class' => 'col-md-3']])->widget(SwitchInput::className(), [
            'type' => SwitchInput::CHECKBOX,
            'options' => ['id' => 'adiantamento_ferias'],
            'pluginOptions' => [
                'onText' => 'Sim',
                'offText' => 'Não'
            ],
        ])
        ?>

    </div>

    <!--------------------------------------------------------------------------------->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Registrar Férias' : 'Editar Registro de Férias', ['id' => $model->idusuario,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs("
    
");

$this->registerJs("
    $('#ferias-tipo').change(function () {
        if ($('#ferias-tipo').val() == 1) {
            $('#adiantamento_decimo').bootstrapSwitch('disabled',true);
            $('#adiantamento_ferias').bootstrapSwitch('disabled',true);
        } else {
            $('#adiantamento_decimo').bootstrapSwitch('disabled',false);
            $('#adiantamento_ferias').bootstrapSwitch('disabled',false);
        }
    });

    if ($('#ferias-tipo').val() == 1) {
        $('#adiantamento_decimo').bootstrapSwitch('disabled',true);
        $('#adiantamento_ferias').bootstrapSwitch('disabled',true);
    } 
");

if (Ferias::find()->where("idusuario = '".$model->idusuario."'AND YEAR(dataSaida) = ".$_GET["ano"])->andWhere(['tipo' => 2])->one() != null) {
    if(Ferias::find()->where(['idusuario' =>$model->idusuario])->andWhere(['YEAR(dataSaida)' => $_GET["ano"]])->andFilterWhere(['or',['adiantamentoDecimo' => 1],['adiantamentoFerias' => 1]])->one() != null){
        $this->registerJs("
            $('#ferias-tipo').change(function () {
                 if ($('#ferias-tipo').val() == 2) {
                    $('#adiantamento_decimo').bootstrapSwitch('disabled',true);
                    $('#adiantamento_ferias').bootstrapSwitch('disabled',true);
                } 
            });   

            $('#adiantamento_decimo').bootstrapSwitch('disabled',true);
            $('#adiantamento_ferias').bootstrapSwitch('disabled',true); 
        ");
    }
}

?>