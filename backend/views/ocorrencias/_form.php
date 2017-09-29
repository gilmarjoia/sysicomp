<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ocorrencias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-lg-8">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><b>Dados da Nova Ocorrência</b></h3>
            </div>

            <div class="panel-body">

			    <?php $form = ActiveForm::begin(); ?>
	
			    <div class="row">
			    <?= $form->field($model, 'codigo', ['options' => ['class' => 'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Codigo</b>") ?>
			    </div>
			    <div class="row">
			    <?= $form->field($model, 'ocorrencia', ['options' => ['class' => 'col-md-5']])->textarea(['rows' => 6])->label("<font color='#FF0000'>*</font> <b>Ocorrência</b>") ?>
				</div>
			    
			    <div class="form-group">
			    	<?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			    </div>

			    <?php ActiveForm::end(); ?>

			</div>
        </div>
    </div>

</div>
