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
$arrayOcorrencias = Ocorrencias::find()->all();
$listData = ArrayHelper::map($arrayOcorrencias,'id','codigo','ocorrencia');
$idUser = Ferias::find()->select("j17_ferias.idusuario")->from('j17_ferias')->where(['nomeusuario' => Yii::$app->user->identity->nome])->one()->idusuario;
//print_r($idUser);

//var_dump($idUser);

?>

<div class="frequencias-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?php if ($model->isNewRecord) echo $form->field($model,'idusuario', ['options' => ['class' => 'col-md-3']])->hiddenInput(['value' => $idUser])->label(false)?>
    </div>

    <div class="row">
        <?php if ($model->isNewRecord) echo $form->field($model,'nomeusuario', ['options' => ['class' => 'col-md-3']])->hiddenInput(['value' => Yii::$app->user->identity->nome])->label(false)?>
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


