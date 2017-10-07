<?php

namespace backend\controllers;

use Yii;
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

    public function actionListar($ano)
    {


        $idUser = Yii::$app->user->identity->id;


        $model = new Frequencias();
        $todosAnosFrequencias = $model->anosFrequencias($idUser);
		
		



        $searchModel = new FrequenciasSearch();
        $dataProvider = $searchModel->searchMinhasFrequencias(Yii::$app->request->queryParams , $idUser ,$ano);

        $model_do_usuario = User::find()->where(["id" => $idUser])->one();

        return $this->render('index', [
            'model_do_usuario' => $model_do_usuario,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'todosAnosFrequencias' => $todosAnosFrequencias,

        ]);
    }

    public function actionListartodos($ano)
    {


        $idUser = Yii::$app->user->identity->id;


        $model = new Frequencias();
        $todosAnosFrequencias = $model->anosFrequencias(null);



        $searchModel = new FrequenciasSearch();
        $dataProvider = $searchModel->searchFrequencias(Yii::$app->request->queryParams , $ano);

        $searchModel2 = new FrequenciasSearch();
        $dataProvider2 = $searchModel2->searchFuncionarios(Yii::$app->request->queryParams , $ano);

        return $this->render('listarTodos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'todosAnosFrequencias' => $todosAnosFrequencias,
        ]);
    }

    public function actionDetalhar($ano,$id,$prof)
    {


        $idUser = $id;

        $model = new Frequencias();

        $ehProf = $prof;



        $todosAnosFrequencias = $model->anosFrequencias($idUser);
        //$totalOcorrencias = $model->frequenciasAno($idUser,$ano);


        $searchModel = new FrequenciasSearch();
        $dataProvider = $searchModel->searchMinhasFrequencias(Yii::$app->request->queryParams , $idUser ,$ano);

        $model_do_usuario = User::find()->where(["id" => $idUser])->one();

        return $this->render('detalhar', [
            'model_do_usuario' => $model_do_usuario,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'todosAnosFrequencias' => $todosAnosFrequencias,
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


            $feriasAno = new Frequencias();
            $anoSaida = date('Y', strtotime($model->dataInicial));
            $totalDiasFrequenciasAno = $feriasAno->frequenciasAno($model->idusuario,$anoSaida);


            $datetime1 = new \DateTime($model->dataInicial);
            $datetime2 = new \DateTime($model->dataFinal);
            $interval = $datetime1->diff($datetime2);
            $diferencaDias =  $interval->format('%a');
            $diferencaDias++;

            if( $diferencaDias < 0 || $interval->format('%R') == "-"){

                $this->mensagens('danger', 'Registro Frequências',  'Datas inválidas!');

                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal =  date('d-m-Y', strtotime($model->dataFinal));

                return $this->render('create', [
                    'model' => $model,
                ]);

            }


            if($model->save()){


                $this->mensagens('success', 'Registro Frequências',  'Registro de Frequência realizado com sucesso!');

                return $this->redirect(['listar', 'ano' => $anoSaida]);

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
            $totalDiasFrequenciasAno = $frequenciasAno->frequenciasAno($model->idusuario, $anoInicio);

            $datetime1 = new \DateTime($model->dataInicial);
            $datetime2 = new \DateTime($model->dataFinal);
            $interval = $datetime1->diff($datetime2);
            $diferencaDias = $interval->format('%a');
            $diferencaDias++;

            if ($diferencaDias < 0 || $interval->format('%R') == "-") {
                $this->mensagens('danger', 'Registro de Frequências', 'Datas inválidas!');
                $model->dataInicial = date('d-m-Y', strtotime($model->dataInicial));
                $model->dataFinal = date('d-m-Y', strtotime($model->dataFinal));
                return $this->render('createsecretaria', [
                    'model' => $model,
                ]);
            }

            if($model->save()){
                $this->mensagens('success', 'Registro de Frequência',  'Registro de Frequência realizado com sucesso!');
                return $this->redirect(['detalhar', 'id' => $model->idusuario, 'ano' => date("Y") ,"prof" => $model_User->professor]);
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Frequencias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'ano' => date("Y")]);
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
