<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use app\models\Ferias;
use app\models\Ocorrencias;
use xj\bootbox\BootboxAsset;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Frequencias */
/* @var $form yii\widgets\ActiveForm */

$arrayOcorrencias = Ocorrencias::find()->select("codigo,ocorrencia")->all();
$numOcorrencias = count($arrayOcorrencias);

for ($i = 0; $i < $numOcorrencias; $i++) {
    $arrayOcorrencias[$i]->ocorrencia =  Ocorrencias::find()->where(["codigo" => $arrayOcorrencias[$i]->codigo])->one()->getCodigo().' - '.$arrayOcorrencias[$i]->ocorrencia;
}

$listData = ArrayHelper::map($arrayOcorrencias,'codigo','ocorrencia','codigo');
?>

<?php
    $this->registerJS('$("#frequencias-codigoocorrencia").change(function(){
                            var lista = document.getElementById("frequencias-codigoocorrencia");
                            var codigo=lista.options[lista.selectedIndex].value;
                            
                            $.ajax({
                                data:{codigo:codigo},
                                url:"'.Url::To(array('ocorrencias/dias')).'",
                                success:function(data){
                                    $("#frequencias-qtddiaspagamento").val(data);
                                },              
                            });
                        });

                        ',View::POS_READY,'my-button-handler');
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