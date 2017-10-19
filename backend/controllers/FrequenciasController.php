<?php

namespace backend\controllers;

use Yii;
use DateTime;
use DatePeriod;
use DateInterval;
use app\models\Frequencias;
use yii\filters\AccessControl;
use common\models\User;
use app\models\FrequenciasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



/**
 * FrequenciasController implements the CRUD actions for Frequencias model.
 */
class FrequenciasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->identity->checarAcesso('professor') || Yii::$app->user->identity->checarAcesso('secretaria'));
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deletesecretaria' => ['POST'],
                    'replicarsecretaria' => ['POST'],
                    'remove',
                ],
            ],
        ];
    }

    /**
     * Lists all Frequencias models.
     * @return mixed
     */
    public function actionIndex()
    {

        $idUser = Yii::$app->user->identity->id;


        $searchModel = new FrequenciasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);// , $idUser);



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListar($ano,$mes)
    {


        $idUser = Yii::$app->user->identity->id;


        $model = new Frequencias();
        $todosAnosFrequencias = $model->anosFrequencias($idUser);
        $todosMesFrequencias = $model->mesFrequencias($idUser);

		



        $searchModel = new FrequenciasSearch();
        $dataProvider = $searchModel->searchMinhasFrequencias(Yii::$app->request->queryParams , $idUser ,$ano,$mes);

        $model_do_usuario = User::find()->where(["id" => $idUser])->one();

        return $this->render('index', [
            'model_do_usuario' => $model_do_usuario,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'todosAnosFrequencias' => $todosAnosFrequencias,
            'todosMesFrequencias' => $todosMesFrequencias,

        ]);
    }

    public function actionListartodos($ano,$mes)
    {


        $idUser = Yii::$app->user->identity->id;


        $model = new Frequencias();
        $todosAnosFrequencias = $model->anosFrequencias(null);
        $todosMesFrequencias = $model->mesFrequencias(null);



        $searchModel = new FrequenciasSearch();
        $dataProvider = $searchModel->searchFrequencias(Yii::$app->request->queryParams , $ano,$mes);

        $searchModel2 = new FrequenciasSearch();
        $dataProvider2 = $searchModel2->searchFuncionarios(Yii::$app->request->queryParams , $ano, $mes);

        return $this->render('listarTodos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'todosAnosFrequencias' => $todosAnosFrequencias,
            'todosMesFrequencias' => $todosMesFrequencias,
        ]);
    }

    public function actionDetalhar($ano,$mes,$id,$prof)
    {


        $idUser = $id;

        $model = new Frequencias();

        $ehProf = $prof;



        $todosAnosFrequencias = $model->anosFrequencias($idUser);
        $todosMesFrequencias = $model->mesFrequencias($idUser);
        //$totalOcorrencias = $model->frequenciasAno($idUser,$ano);


        $searchModel = new FrequenciasSearch();
        $dataProvider = $searchModel->searchMinhasFrequencias(Yii::$app->request->queryParams , $idUser ,$ano,$mes);

        $model_do_usuario = User::find()->where(["id" => $idUser])->one();

        return $this->render('detalhar', [
            'model_do_usuario' => $model_do_usuario,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'todosAnosFrequencias' => $todosAnosFrequencias,
            'todosMesFrequencias' => $todosMesFrequencias,
            'id' => $id,
        ]);
    }

    /**
     * Displays a single Frequencias model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Frequencias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new Frequencias();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    public function actionCreate($ano)
    {

        $model = new Frequencias();
        $model->idusuario = Yii::$app->user->identity->id;
        $model->nomeusuario = Yii::$app->user->identity->nome;

        $ehProfessor = Yii::$app->user->identity->professor;
        $ehSecretario = Yii::$app->user->identity->secretaria;


        if ($model->load(Yii::$app->request->post())) {


            $model->dataInicial = date('Y-m-d', strtotime($model->dataInicial));
            $model->dataFinal =  date('Y-m-d', strtotime($model->dataFinal));


            $frequenciasAno = new Frequencias();
            $anoSaida = date('Y', strtotime($model->dataInicial));
            $mesSaida = date('m', strtotime($model->dataInicial));
            $totalDiasFrequenciasAno = $frequenciasAno->frequenciasAno($model->idusuario,$anoSaida);
            $totalDiasFrequenciasMes = $frequenciasAno->frequenciasMes($model->idusuario,$mesSaida);


            $datetime1 = new \DateTime($model->dataInicial);
            $datetime2 = new \DateTime($model->dataFinal);
            $interval = $datetime1->diff($datetime2);
            $diferencaDias =  $interval->format('%a');
            $diferencaDias++;

            if($model->verificarSeDataEhValida($model->idusuario,$anoSaida,$mesSaida,$model->dataInicial,$model->dataFinal)==0){
                $this->mensagens('danger', 'Registro Frequências',  'Falha no Registro de Frequência, já existe uma ocorrência dentro da data especificada!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        
            if( $diferencaDias < 0 || $interval->format('%R') == "-" ){

                $this->mensagens('danger', 'Registro Frequências',  'Datas inválidas!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('create', [
                    'model' => $model,
                ]);

            }

            $dataAtual = strtotime(date('d-m-Y'));
            $dataInicialFrequencia = strtotime($model->dataInicial);
            $dataFinalFrequencia = strtotime($model->dataFinal);

            if( $dataInicialFrequencia > $dataAtual){

                $this->mensagens('danger', 'Registro Frequências',  'Falha no Registro de Frequência, você não pode lançar em uma data futura!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            if($model->save()){


                $this->mensagens('success', 'Registro Frequências',  'Registro de Frequência realizado com sucesso!');

                return $this->redirect(['listar', 'ano' => $anoSaida,'mes' => $mesSaida]);

            }else {

                $this->mensagens('danger', 'Registro Frequências', 'Não foi possível registrar a frequencia.');

            }

            $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
            $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

            return $this->render('create', [
                'model' => $model,
            ]);


        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



    public function actionCreatesecretaria($id)
    {
        $model = new Frequencias();
        $model_User = User::find()->where(["id" => $id])->one();


        $model->idusuario;
        $model->nomeusuario = $model_User->nome;

        //print_r($dataRegistro);

        if ($model->load(Yii::$app->request->post())) {
            $model->dataInicial = date('Y-m-d', strtotime($model->dataInicial));
            $model->dataFinal = date('Y-m-d', strtotime($model->dataFinal));

            $frequenciasAno = new Frequencias();
            $anoInicio = date('Y', strtotime($model->dataInicial));
            $mesInicio = date('m', strtotime($model->dataInicial));
            $totalDiasFrequenciasAno = $frequenciasAno->frequenciasAno($model->idusuario, $anoInicio);
            $totalDiasFrequenciasMes = $frequenciasAno->frequenciasMes($model->idusuario, $mesInicio);

            $datetime1 = new \DateTime($model->dataInicial);
            $datetime2 = new \DateTime($model->dataFinal);
            $interval = $datetime1->diff($datetime2);
            $diferencaDias = $interval->format('%a');
            $diferencaDias++;

            if($model->verificarSeDataEhValida($model->idusuario,$anoInicio,$mesInicio,$model->dataInicial,$model->dataFinal)==0){
                $this->mensagens('danger', 'Registro Frequências',  'Falha no Registro de Frequência, já existe uma ocorrência dentro da data especificada!!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('createsecretaria', [
                    'model' => $model,
                ]);
            }

            if ($diferencaDias < 0 || $interval->format('%R') == "-") {
                $this->mensagens('danger', 'Registro de Frequências', 'Datas inválidas!');
                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal = date('d-m-Y', strtotime($model->dataFinal));
                return $this->render('createsecretaria', [
                    'model' => $model,
                ]);
            }

            $dataAtual = strtotime(date('d-m-Y'));
            $dataInicialFrequencia = strtotime($model->dataInicial);
            $dataFinalFrequencia = strtotime($model->dataFinal);

            if( $dataInicialFrequencia > $dataAtual){

                $this->mensagens('danger', 'Registro Frequências',  'Falha no Registro de Frequência, você não pode lançar em uma data futura!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('createsecretaria', [
                    'model' => $model,
                ]);
            }

            if($model->save()){
                $this->mensagens('success', 'Registro de Frequência',  'Registro de Frequência realizado com sucesso!');
                return $this->redirect(['detalhar', 'id' => $model->idusuario, 'ano' => date("Y"),'mes' => date('m') ,"prof" => $model_User->professor]);
            }
            else {
                $this->mensagens('danger', 'Registro de Frequência', 'Algo deu errado');
            }
            $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
            $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));
            return $this->render('createsecretaria', [
                'model' => $model,
                'nome' => $model->nomeusuario,
            ]);
        } else {
            return $this->render('createsecretaria', [
                'model' => $model,
                'nome' => $model->nomeusuario,
            ]);
        }
    }

    /**
     * Updates an existing Frequencias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$ano,$mes)
    {

        $model = $this->findModel($id);
        $model_User = User::find()->where(["id" => $model->idusuario])->one();

        $ehProfessor = Yii::$app->user->identity->professor;
        $ehCoordenador = Yii::$app->user->identity->coordenador;
        $ehSecretario = Yii::$app->user->identity->secretaria;

        $datetime1Anterior = new \DateTime($model->dataInicial);
        $datetime2Anterior = new \DateTime($model->dataFinal);
        $intervalAnterior = $datetime1Anterior->diff($datetime2Anterior);
        $AnteriordiferencaDias = $intervalAnterior->format('%a');
        $AnteriordiferencaDias++;

        //$anteriorTipo = $model->tipo;

        if ($model->load(Yii::$app->request->post())) {

            $model->dataInicial = date('Y-m-d', strtotime($model->dataInicial));
            $model->dataFinal = date('Y-m-d', strtotime($model->dataFinal));


            $frequenciasAno = new Frequencias();
            $anoSaida = date('Y', strtotime($model->dataInicial));
            $mesSaida = date('m', strtotime($model->dataInicial));
            $totalDiasFrequenciasAno = $frequenciasAno->frequenciasAno($model->idusuario, $anoSaida);
            $totalDiasFrequenciasMes = $frequenciasAno->frequenciasMes($model->idusuario,$mesSaida);


            $datetime1 = new \DateTime($model->dataInicial);
            $datetime2 = new \DateTime($model->dataFinal);
            $interval = $datetime1->diff($datetime2);
            $diferencaDias = $interval->format('%a');
            $diferencaDias++;



            if ($diferencaDias < 0 || $interval->format('%R') == "-") {

                $this->mensagens('danger', 'Registro Frequências', 'Datas inválidas!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal = date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('update', [
                    'model' => $model,
                ]);

            }

            $dataAtual = strtotime(date('d-m-Y'));
            $dataInicialFrequencia = strtotime($model->dataInicial);
            $dataFinalFrequencia = strtotime($model->dataFinal);

            if( $dataInicialFrequencia > $dataAtual){

                $this->mensagens('danger', 'Registro Frequências',  'Falha no Registro de Frequência, você não pode lançar em uma data futura!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('update', [
                    'model' => $model,
                ]);
            }


            if (($ehProfessor == 1) && $model->save()) {

                $this->mensagens('success', 'Registro Frequências', 'Registro de Frequências realizado com sucesso!');

                return $this->redirect(['detalhar', "id" => $model->idusuario, "ano" => $_GET["ano"],"mes" =>$_GET["mes"], "prof" => $model_User->professor]);

            } else if ($ehSecretario == 1 && $model->save()) {

                $this->mensagens('success', 'Registro Frequências', 'Registro de Freqências realizado com sucesso!');

                return $this->redirect(['detalhar', "id" => $model->idusuario, "ano" => $_GET["ano"], "mes" =>$_GET["mes"], "prof" => $model_User->secretaria]);

            }

            $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
            $model->dataFinal = date('d-m-Y', strtotime($model->dataFinal));

            return $this->render('update', [
                'model' => $model,
            ]);

        } else {

            $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
            $model->dataFinal = date('d-m-Y', strtotime($model->dataFinal));

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionReplicarsecretaria($id, $ano,$mes,$idUsuario, $prof)
    {

        $model = $this->findModel($id);
        $novaFrequencia = new Frequencias();

        $novaFrequencia->idusuario = $model->idusuario;
        $novaFrequencia->nomeusuario = $model->nomeusuario;
        $novaFrequencia->codigoOcorrencia = $model->codigoOcorrencia;

        $dataInicial = $model->dataInicial; 
        $dataFinal = $model->dataFinal;
        
        if ($dataInicial == $dataFinal){
            $dataInicial = new DateTime($dataInicial);
            $dataInicial->add(new DateInterval('P1D'));
            $novaFrequencia->dataInicial = date_format($dataInicial, 'Y-m-d');
            $novaFrequencia->dataFinal = $novaFrequencia->dataInicial;
        }else{
            $dataInicial = new DateTime($dataInicial);
            $dataInicial->add(new DateInterval('P1M'));
            $novaFrequencia->dataInicial = date_format($dataInicial, 'Y-m-d');
            $dataFinal = new DateTime($dataFinal);
            $dataFinal->add(new DateInterval('P1M'));
            $novaFrequencia->dataFinal = date_format($dataFinal, 'Y-m-d');;
        }

        $anoSaida = date('Y', strtotime($novaFrequencia->dataInicial));
        $mesSaida = date('m',strtotime($novaFrequencia->dataInicial));

        if($novaFrequencia->verificarSeDataEhValida($novaFrequencia->idusuario,$anoSaida,$mesSaida,$novaFrequencia->dataInicial,$novaFrequencia->dataFinal)==0){
            $this->mensagens('danger', 'Registro Frequências', 'Falha no Registro de Frequência, já existe uma ocorrência dentro da data especificada!');
        }else{
            if ($novaFrequencia->save()) {
                $this->mensagens('success', 'Registro Frequências', 'Registro de Frequência replicado com sucesso!');
            }
        }     

        return $this->redirect(['detalhar', 'id' => $idUsuario, 'ano' => $ano, 'mes' => $mes, 'prof' => $prof]);
    }

    /**
     * Deletes an existing Frequencias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$ano,$mes)
    {
        $this->findModel($id)->delete();

        $this->mensagens('success', 'Registro Frequências', 'Registro de Frequências excluído com sucesso!');

        return $this->redirect(['listar', 'ano' => $ano, 'mes' => $mes]);
    }

    public function actionRemove()
    {
        $checkedIDs=$_GET['checked'];
        foreach($checkedIDs as $id)
            $this->findModel($id)->delete();
        $this->mensagens('success', 'Registro Frequências', 'Registros de Frequências excluídos com sucesso!');
    }

    public function actionDeletesecretaria($id, $ano, $mes, $idUsuario, $prof)
    {

        $this->findModel($id)->delete();

        $this->mensagens('success', 'Registro Frequências', 'Registro de Frequências excluído com sucesso!');

        return $this->redirect(['detalhar', 'id' => $idUsuario, 'ano' => $ano,'mes' => $mes, 'prof' => $prof]);
    }

    /**
     * Finds the Frequencias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Frequencias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Frequencias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* Envio de mensagens para views
       Tipo: success, danger, warning*/
    protected function mensagens($tipo, $titulo, $mensagem)
    {
        Yii::$app->session->setFlash($tipo, [
            'type' => $tipo,
            'icon' => 'home',
            'duration' => 5000,
            'message' => $mensagem,
            'title' => $titulo,
            'positonY' => 'top',
            'positonX' => 'center',
            'showProgressbar' => true,
        ]);
    }
}
