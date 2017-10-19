<?php

namespace app\models;
use yiibr\brvalidator\CpfValidator;
use Yii;

class User extends \yii\db\ActiveRecord
{
    public $password;
    public $password_repeat;
    public $lattesUpload;



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'visualizacao_candidatos', 'visualizacao_candidatos_finalizados', 'visualizacao_cartas_respondidas'], 'required'],
            [['password_repeat'], 'required', 'when' => function($model){ return $model->password != "";}, 'whenClient' => "function (attribute, value) {
                return $('#user-password').val() != '';}"],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Esta senha não é igual à anterior", 'when' => function($model){ return $model->password != "";}, 'whenClient' => "function (attribute, value) {
                return $('#user-password').val() != '';}"],
            [['status', 'idLattes', 'idRH'], 'integer'],
            ['password', 'string', 'min' => 6],
            [['lattesUpload'], 'file', 'extensions' => 'xml'],
            [['username'], CpfValidator::className(), 'message' => 'CPF Inválido'],
            [['visualizacao_candidatos', 'visualizacao_candidatos_finalizados', 'visualizacao_cartas_respondidas'], 'safe'],
            [['nome', 'username', 'password_hash', 'password_reset_token', 'email', 'endereco'], 'string', 'max' => 255],
            [['auth_key', 'cargo', 'turno'], 'string', 'max' => 32],
            [['unidade'], 'string', 'max' => 60],
            [['formacao'], 'string', 'max' => 300],
            [['resumo'], 'string'],
            [['nivel'], 'string', 'max' => 6],
            [['created_at', 'updated_at', 'siape', 'dataIngresso','regime','ultimaAtualizacao'], 'string', 'max' => 10],
            [['telcelular', 'telresidencial', 'titulacao', 'classe', 'alias'], 'string', 'max' => 20],
            [['administrador', 'coordenador', 'secretaria', 'professor', 'aluno'], 'string', 'max' => 1],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'username' => 'CPF',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Data de Criação',
            'updated_at' => 'Updated At',
            'visualizacao_candidatos' => 'Visualizacao Candidatos',
            'visualizacao_candidatos_finalizados' => 'Visualizacao Candidatos Finalizados',
            'visualizacao_cartas_respondidas' => 'Visualizacao Cartas Respondidas',
            'administrador' => 'Administrador',
            'coordenador' => 'Coordenador',
            'secretaria' => 'Secretaria',
            'professor' => 'Professor',
            'aluno' => 'Aluno',
            'siape' => 'Siape',
            'perfis' => 'Perfil(s)',
            'cargo' => 'Cargo',
            'dataIngresso' => 'Data de Ingresso',
            'telcelular' => 'Telefone Celular',
            'telresidencial' => 'Telefone Residencial',
            'unidade' => 'Unidade em que atua',
            'titulacao' => 'Titulação Máxima',
            'cargo' => 'Cargo ocupado',
            'classe' => 'Classe',
            'nivel' => 'Nível',
            'regime' => 'Regime de Dedicação',
            'turno' => 'Turno de Trabalho',
            'idLattes' => 'Código do Currículo Lattes',
            'idRH' => 'Nº de Contrato no RH',
            'alias' => 'Tag para página',
            'lattesUpload' => 'Xml Lattes',
        ];
    }

    public function setPassword(){
        if($this->password != ""){
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
    }

    public function getPerfis(){
        $perfis = [];

        if($this->administrador)
            $perfis[] = "Administrador";
        if($this->secretaria)
            $perfis[] = "Secretaria";
        if($this->coordenador)
            $perfis[] = "Coordenador";
        if($this->professor)
            $perfis[] = "Professor";
        if($this->aluno)
            $perfis[] = "Aluno";

        return implode($perfis,' | ');
    }

    public function feriasAno($idusuario,$ano,$tipo){

       $model_ferias = new Ferias();
       $model_ferias = $model_ferias->feriasAno($idusuario,$ano,$tipo);

        return $model_ferias;
    }

    public function frequenciasAno($idusuario,$ano){

        $model_frequencias = new Frequencias();
        $model_frequencias = $model_frequencias->frequenciasAno($idusuario,$ano);

        return $model_frequencias;
    }

    public function contarOcorrencias($idusuario,$ano,$mes){
        $model_frequencias = new Frequencias();
        $model_frequencias = $model_frequencias->contarOcorrencias($idusuario,$ano,$mes);

        return $model_frequencias;
    }

    public function contarDiasPagar($idusuario,$ano,$mes){
        $model_frequencias = new Frequencias();
        $model_frequencias = $model_frequencias->contarDiasPagar($idusuario,$ano,$mes);

        return $model_frequencias;
    }

    public function diasRestantesParaGozoDeFerias($idusuario){

       $model_ferias = new Ferias();
       $model_ferias = $model_ferias->diasRestantesParaGozoDeFerias($idusuario);

        return $model_ferias;
    }

    public function getAlunos($idusuario)
    {
        $model_alunos = new Aluno();
        $model_alunos = $model_alunos->orientados($idusuario);
        return $model_alunos;
    }

    public function beforeDelete(){
        return Aluno::find()->where(['orientador' => $this->id])->count() == 0 || $this->administrador == 0 ? true : false;
    }
}
