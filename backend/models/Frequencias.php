<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_frequencias".
 *
 * @property int $id
 * @property int $idusuario
 * @property string $nomeusuario
 * @property string $dataInicial
 * @property string $dataFinal
 * @property string $codigoOcorrencia
 */
class Frequencias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_frequencias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id', 'idusuario', 'nomeusuario', 'dataInicial', 'dataFinal', 'codigoOcorrencia'], 'required'],
            [['id', 'idusuario'], 'integer'],
            [['dataInicial', 'dataFinal'], 'safe'],
            [['nomeusuario'], 'string', 'max' => 60],
            [['codigoOcorrencia'], 'string', 'max' => 4],
            //[['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idusuario' => 'Idusuario',
            'nomeusuario' => 'Nomeusuario',
            'dataInicial' => 'Data Inicial',
            'dataFinal' => 'Data Final',
            'codigoOcorrencia' => 'Codigo Ocorrencia',
        ];
    }
}
