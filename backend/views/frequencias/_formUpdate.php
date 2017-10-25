<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use app\models\Ocorrencias;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */
/* @var $form yii\widgets\ActiveForm */

$arrayOcorrencias = Ocorrencias::find()->select("codigo,ocorrencia")->all();
$numOcorrencias = count($arrayOcorrencias);

for ($i = 0; $i < $numOcorrencias; $i++) {
    $arrayOcorrencias[$i]->ocorrencia = $arrayOcorrencias[$i]->codigo.' - '.$arrayOcorrencias[$i]->ocorrencia; 
}

$listData = ArrayHelper::map($arrayOcorrencias,'codigo','ocorrencia','codigo');
?>

    <div class="ferias-form">

        <?php $form = ActiveForm::begin(); ?>

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
            <?php echo $form->field($model, 'codigoOcorrencia' , ['options' => ['class' => 'col-md-6']])->dropDownlist($listData, ['prompt' => 'Selecione um código de Ocorrência'])->label("<font color='#FF0000'>*</font> <b>Código da Ocorrência:</b>");?>
        </div>

        <div class = "row">
            <?php echo $form->field($model, 'qtdDiasPagamento' , ['options' => ['class' => 'col-md-6']])->textInput(['min' => 0, 'max' => 30])->label("<font color='#FF0000'>*</font> <b>Quantidade de dias para pagamento:</b>");?>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Registrar Frequências' : 'Editar Registro de Frequências', ['id' => $model->idusuario,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php