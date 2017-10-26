<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_ocorrencias".
 *
 * @property int $id
 * @property string $codigo
 * @property string $ocorrencia
 */
class Ocorrencias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_ocorrencias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo','ocorrencia','naooficial'],'required'],
            [['id'], 'integer'],
            [['naooficial'], 'boolean'],
            [['ocorrencia'], 'string'],
            [['codigo'], 'string', 'max' => 4],
            [['codigo','ocorrencia'],'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'ocorrencia' => 'Ocorrencia',
            'naooficial' => 'Não Oficial'
        ];
    }

    public function getCodigo(){
        if ($this->naooficial){
            return 'X'.$this->codigo;
        }else{
            return $this->codigo;
        }
    }

}
