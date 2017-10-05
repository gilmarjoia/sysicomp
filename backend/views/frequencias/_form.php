<?php



use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use app\models\Ocorrencias;


/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */
/* @var $form yii\widgets\ActiveForm */


//$arrayOcorrencias = Ocorrencias::find()->select("j17_ocorrencias.codigo")->all();
$arrayOcorrencias = Ocorrencias::find()->select("j17_ocorrencias.codigo")->from('j17_ocorrencias')->one();
//print_r($arrayOcorrencias);
//var_dump($arrayOcorrencias);

?>

<div class="ferias-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class = "row">
        <?php if ($model->isNewRecord) echo $form->field($model, 'codigoOcorrencia' , ['options' => ['class' => 'col-md-3']])->dropDownlist($arrayOcorrencias, ['prompt' => 'Selecione um código de Ocorrência'])->label("<font color='#FF0000'>*</font> <b>Código da Ocorrência:</b>");?>
    </div>


    <div class = "row">
        <?= $form->field($model, 'dataInicial', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
            'language' => Yii::$app->language,
            'options' => ['placeholder' => 'Selecione a Data Inicial da Falta ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data Inícial:</b>")
        ?>
    </div>

    <div class = "row">

        <?= $form->field($model, 'dataFinal', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
            'language' => Yii::$app->language,
            'options' => ['placeholder' => 'Selecione a Data final da Falta ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data Final:</b>")
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Registrar Frequências' : 'Editar Registro de Frequências', ['id' => $model->idusuario,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



