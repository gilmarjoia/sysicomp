<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use yii\widgets\MaskedInput;

?>

<div class="edital-form">
	<div class="grid">
	    <?php $form = ActiveForm::begin(); 	?>
		

			<div class="row">
			<?= $form->field($model, 'local', ['options' => ['class' => 'col-md-3']])->textInput(['type' => 'text', 'maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Local:</b>") ?>           
			</div>
		    <div class="row">
				<?= $form->field($model, 'tipo', ['options' => ['class' => 'col-md-3']])->radioList([1 => 'Nacional', 2 => 'Internacional'])->label("<font color='#FF0000'>*</font> <b>Tipo de Viagem</b>"); ?>
			</div>			
 		    <div class="row">
		        <?= $form->field($model, 'datasaida', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
	                'language' => Yii::$app->language,
	                'options' => ['placeholder' => 'Selecione a Data de Saída ...',],
				    'pluginOptions' => [
				        'format' => 'dd-mm-yyyy',
				        'todayHighlight' => true
				    ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data de Saída:</b>")
		    ?>
				<?= $form->field($model, 'dataretorno', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
	                'language' => Yii::$app->language,
	                'options' => ['placeholder' => 'Selecione a Data de Retorno ...',],
				    'pluginOptions' => [
				        'format' => 'dd-mm-yyyy',
				        'todayHighlight' => true
				    ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data de Retorno:</b>")
		    ?>
		    </div>

			<div class="row">
				<?= $form->field($model, 'justificativa', ['options' => ['class' => 'col-md-3']])->textarea(['rows' => 6])->label("<font color='#FF0000'>*</font> <b>Justificativa:</b><br>") ?>
			</div>
			<div class="row">
				<?= $form->field($model, 'reposicao', ['options' => ['class' => 'col-md-3']])->textArea(['rows' => '6'])->label("<font color='#FF0000'>*</font> <b>Plano de Reposição de Aulas:</b>") ?>
		    </div>

		    <div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? 'Enviar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>

	    <?php ActiveForm::end(); ?>

	</div>


</div>
