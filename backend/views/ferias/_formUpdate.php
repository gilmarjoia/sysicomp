<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\Ferias */
/* @var $form yii\widgets\ActiveForm */
$arrayTipoferias = array ("1" => "Usufruto", "2" => "Oficial");

?>

<div class="ferias-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class = "row" id="tipo">
    <?= $form->field($model, 'tipo' , ['options' => ['class' => 'col-md-3']])->dropDownlist($arrayTipoferias, ['prompt' => 'Selecione um tipo de Férias'])->label("<font color='#FF0000'>*</font> <b>Tipo:</b>")?>
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
            'pluginOptions' => [
                'onText' => 'Sim',
                'offText' => 'Não'
            ],
        ]);
        ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'adiantamentoFerias', ['options' => ['class' => 'col-md-3']])->widget(SwitchInput::className(), [
            'type' => SwitchInput::CHECKBOX,
            'pluginOptions' => [
                'onText' => 'Sim',
                'offText' => 'Não'
            ],
        ]);
        ?>
    </div>

    <!--------------------------------------------------------------------------------->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Registrar Férias' : 'Editar Registro de Férias', ['id' => $model->idusuario,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
