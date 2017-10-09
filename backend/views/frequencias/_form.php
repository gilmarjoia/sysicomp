<?php



use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use app\models\Ocorrencias;
use app\models\Ferias;

/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */
/* @var $form yii\widgets\ActiveForm */


//$arrayOcorrencias = Ocorrencias::find()->select("j17_ocorrencias.codigo")->column();
$arrayOcorrencias = Ocorrencias::find()->select("codigo,ocorrencia")->all();
$listData = ArrayHelper::map($arrayOcorrencias,'codigo','codigo','ocorrencia');
$nomeusuario = Ferias::find()->select("j17_ferias.nomeusuario")->from('j17_ferias')->where(['idusuario' => $_GET["id"]])->one()->nomeusuario;
//print_r($idUser);

//var_dump($idUser);

?>

<div class="frequencias-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php if ($model->isNewRecord) echo $form->field($model,'idusuario', ['options' => ['class' => 'col-md-3']])->hiddenInput(['value' => $_GET["id"]])->label(false)?>
    </div>

    <div class="row">
        <?php if ($model->isNewRecord) echo $form->field($model,'nomeusuario', ['options' => ['class' => 'col-md-3']])->hiddenInput(['value' => $nomeusuario])->label(false)?>
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

    <div class = "row">
        <?php echo $form->field($model, 'codigoOcorrencia' , ['options' => ['class' => 'col-md-3']])->dropDownlist($listData, ['prompt' => 'Selecione um código de Ocorrência'])->label("<font color='#FF0000'>*</font> <b>Código da Ocorrência:</b>");?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Registrar Frequências' : 'Alterar Registro de Frequências', ['id' => $model->idusuario,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


