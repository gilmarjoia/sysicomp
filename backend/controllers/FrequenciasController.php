<?php

namespace backend\controllers;

use Yii;
use mPDF;
use DateTime;
use DatePeriod;
use DateInterval;
use app\models\Frequencias;
use app\models\Ocorrencias;
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
        //$dataProvider = $searchModel->searchFrequencias(Yii::$app->request->queryParams , $ano,$mes);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams , $ano,$mes);

        //$searchModel2 = new FrequenciasSearch();
        //$dataProvider2 = $searchModel2->searchFuncionarios(Yii::$app->request->queryParams , $ano, $mes);

        return $this->render('listarTodos', [
            'searchModel' => $searchModel,
            //'searchModel2' => $searchModel2,
            'dataProvider' => $dataProvider,
            //'dataProvider2' => $dataProvider2,
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

            if($model->verificarSeDataEhValida($model->idusuario,$anoSaida,$mesSaida,$model->dataInicial,$model->dataFinal)!=-1){
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

            if($model->verificarSeDataEhValida($model->idusuario,$anoInicio,$mesInicio,$model->dataInicial,$model->dataFinal)!=-1){
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

            $idFrequencia = $model->verificarSeDataEhValida($model->idusuario,$ano,$mes,$model->dataInicial,$model->dataFinal);

            if($idFrequencia != -1 && $idFrequencia != $id){
                $this->mensagens('danger', 'Registro Frequências',  'Falha no Registro de Frequência, já existe uma ocorrência dentro da data especificada!!');

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

                $this->mensagens('success', 'Registro Frequências', 'Registro de Frequências realizado com sucesso!');

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
        $novaFrequencia->qtdDiasPagamento = $model->qtdDiasPagamento;

        $dataInicial = $model->dataInicial; 
        $dataFinal = $model->dataFinal;
        
        if ($dataInicial == $dataFinal){
            //adicionando 1 dia
            $dataInicial = new DateTime($dataInicial);
            $dataInicial->add(new DateInterval('P1D'));
            $novaFrequencia->dataInicial = date_format($dataInicial, 'Y-m-d');
            $novaFrequencia->dataFinal = $novaFrequencia->dataInicial;
        }else{
            //verifica se é um registro de 1 mês inteiro
            $diferenca = strtotime($dataFinal) - strtotime($dataInicial);
            $dias = floor($diferenca / (60 * 60 * 24))+1;
            
            //adicionando 1 mês
            $dataInicial = new DateTime($dataInicial);
            $dataInicial->add(new DateInterval('P1M'));
                
            $dataFinal = new DateTime($dataFinal);
            $dataFinal->add(new DateInterval('P1M'));

            if($dias>27){//caso seja 1 mês inteiro replica para o próximo mês inteiro
                $mesInicial= date_format($dataInicial, 'm');
                $ultimoDiaMes = date("Y-m-t", mktime(0,0,0,$mesInicial,'01',$ano));
                $dataFinal = new DateTime($ultimoDiaMes);
            }else{ //caso seja menos que 1 mês inteiro só replica o intervalo de data requerido
                
                //verificando se o proximo mes tem os dias que estao sendo replicados
                $dataDeOrigem = new DateTime($model->dataInicial);
                $mesDeOrigem = date_format($dataDeOrigem, 'm');
                $mesInicial = date_format($dataInicial, 'm');
                $mesFinal = date_format($dataFinal, 'm');
                $diferencaMes = $mesInicial - $mesDeOrigem;
                            
                if ($mesInicial != $mesFinal || $diferencaMes>1){
                    $ultimoDiaMes = date("Y-m-t", mktime(0,0,0,$mesInicial,'01',$ano));
                    $dataFinal = new DateTime($ultimoDiaMes);
                    
                    if($dataInicial > $dataFinal || $diferencaMes>1){
                        $this->mensagens('danger', 'Registro Frequências', 'Falha no Registro de Frequência, A data solicitada não existe no mês seguinte!');
                        return $this->redirect(['detalhar', 'id' => $idUsuario, 'ano' => $ano, 'mes' => $mes, 'prof' => $prof]); 
                    }
                }
            }    
            $novaFrequencia->dataInicial = date_format($dataInicial, 'Y-m-d');
            $novaFrequencia->dataFinal = date_format($dataFinal, 'Y-m-d');
        }

        $anoSaida = date('Y', strtotime($novaFrequencia->dataInicial));
        $mesSaida = date('m',strtotime($novaFrequencia->dataInicial));

        if($novaFrequencia->verificarSeDataEhValida($novaFrequencia->idusuario,$anoSaida,$mesSaida,$novaFrequencia->dataInicial,$novaFrequencia->dataFinal)!=-1){
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

    public function actionPrintreport($ano,$mes)
    {
        define('_MPDF_TTFONTDATAPATH',Yii::getAlias('@runtime/mpdf'));
        $pdf = new mPDF('utf-8','A4-L','','','15','15','40','30');
        
        $pdf->SetHTMLHeader
        ('
            <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt;">
                <tr width="100%">
                    <td width="10%" align="left" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/logo-brasil.jpg" height="60px" width="60px"> </td>
                    <td width="35%" align="center" style="vertical-align: middle; font-family: Times New Roman; font-weight: bold; font-size: 150%;">  UNIVERSIDADE FEDERAL DO AMAZONAS <br> PRÓ - REITORIA DE GESTÃO DE PESSOAS 
                        <div align="center" width="75%" style="vertical-align: middle; font-family: Times New Roman; font-size: 75%;">Amazonas - Brasil | depes@ufam.edu.br | +55 (92) 3305-1478/1479</div>
                    </td>
                    <td width="10%" align="right" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/ufam.jpg" height="60px" width="50px"> </td>
                    <td width="30%" align="right">          
                        <table border="1" width="100%" style="border-collapse: collapse;font-family: Arial;">
                            <tr>
                                <td height="35px" align="left" style="font-size:75%; font-weight: bold; vertical-align: top;">UNIDADE:</td>
                            </tr>
                            <tr>
                                <td height="35px" align="left" style="font-size:75%; font-weight: bold; vertical-align: top;">DEPARTAMENTO/COORDENAÇÃO:</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>            
        ');
        $pdf->SetHTMLFooter
        ('
        <table border="1" width="100%" style="border-collapse: collapse;font-family: Arial;">
            <tr>
                <td height="50px" align="center">  '.date('d-m-Y').'  </td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
            </tr>
            <tr>
                <td align="center" style="font-size:75%; font-weight: bold;">Data de Elaboração</td>
                <td align="center" style="font-size:75%; font-weight: bold;">Responsável pela elaboração do Boletim</td>
                <td align="center" style="font-size:75%; font-weight: bold;">Assinatura/Carimbo<br>Chefia Imediata da Unidade de Exercício</td>
                <td align="center" style="font-size:75%; font-weight: bold;">Assinatura/Carimbo<br>Direção da Unidade de Lotação</td>
            </tr>
        </table>
        ');
        $pdf->WriteHTML
        ('
        <table border="1" width="100%" style="border-collapse: collapse;font-family: Arial;">
            <tr>
                <th height="40px" align="center" colspan="4" style="font-weight: bold;font-size: 150%;"> BOLETIM  DE  FREQUÊNCIA </th>    
                <th height="40px" align="center" colspan="1" style="font-weight: bold;font-size: 80%;">MÊS:<br>10 </th>
                <th height="40px" align="center" colspan="1" style="font-weight: bold;font-size: 80%;">ANO:<br>2017</th>
                <th height="40px" align="center" colspan="1" style="font-weight: bold;font-size: 80%;">FOLHA:<br>00/00</th>
            </tr>
            <tr style="background-color: #E6E6FA;">
                <th align="center" colspan="7" style="color:red;font-size: 90%;font-weight: normal;"><b>ATENÇÃO:</b> ESTE BOLETIM DEVE SER PREENCHIDO EM DUAS VIAS E UMA DELAS SERÁ ENTREGUE AO DAPES/PROGESP ATÉ O 5º DIA ÚTIL DO MÊS SEGUINTE.</th>
            </tr>
            <tr>
                <th height="30px" align="center" colspan="3" style="font-weight: bold;font-size: 100%;">IDENTIFICAÇÃO DO SERVIDOR</th>
                <th height="30px" align="center" colspan="4" style="font-weight: bold;font-size: 100%;">ANÁLISE DO PONTO</th>
            </tr>
            <tr align="center">
                <!-- Planilha -->
                <th width="8%" rowspan="2" style="font-size: 70%; font-weight: bold;">MATRÍCULA<br>SIAPE</th>
                <th width="30%" rowspan="2" style="font-size: 70%; font-weight: bold;">NOME</th>
                <th width="12%" rowspan="2" style="font-size: 70%; font-weight: bold;">CARGO/FUNÇÃO</th>
                <th width="7%" rowspan="2" style="font-size: 70%; font-weight: bold;">N° de dias<br>para<br>pagamento</th>
                <th align="center" width="23%" colspan=2 style="font-size: 70%; font-weight: bold;">OCORRÊNCIAS</th>
                <th width="20%" rowspan="2" style="font-size: 70%; font-weight: bold;">CITE AQUI OS DIAS OU PERÍODO DA OCORRÊNCIA ASSINALADA</th>
            </tr>  
            <tr>
                <th width="5%" align="center" style="font-size: 70%; font-weight: bold;">CÓDIGO</td>
                <th width="18%" align="center" style="font-size: 70%; font-weight: bold;">ASSUNTO</td>
            </tr>
            
        ');

        $servidores = User::find()->where('professor=1 or secretaria=1')->all();
        
        foreach($servidores as $servidor)
        {
            $Frequencias = Frequencias::find()->where(["idusuario" => $servidor->id])->andWhere(["Year(dataInicial)" => $ano])->andWhere(["Month(dataInicial)" => $mes])->all();
            
            $diasPagamento = 30;
            $countFrequencias = count($Frequencias);
            $rowspan=$countFrequencias;

            if($countFrequencias==0){
                $rowspan=1;
            }else{
                foreach ($Frequencias as $frequencia) {
                    if ($frequencia->qtdDiasPagamento<$diasPagamento){
                        $diasPagamento = $frequencia->qtdDiasPagamento;
                    }
                }
            }
            
            $pdf->WriteHtml
            ('
            <tr>
                <td align="center" height="20px" rowspan="'.$rowspan.'">'.$servidor->siape.'<!-- Matrícula SIAPE--></td>
                <td rowspan="'.$rowspan.'">'.$servidor->nome.'<!-- Nome do Servidor--></td>
                <td align="center" rowspan="'.$rowspan.'">'.$servidor->cargo.'<!-- Cargo/Função--></td>
                <td align="center" rowspan="'.$rowspan.'">'.$diasPagamento.'<!-- N° Dias para Pagamento--></td>

                <td height="20px">'.($countFrequencias == 0 || Ocorrencias::find()->where(["codigo" => $Frequencias[0]->codigoOcorrencia])->one()->naooficial ? "" : $Frequencias[0]->codigoOcorrencia).'<!-- Codigo--></td>
                <td style="font-size: 70%;">'.($countFrequencias > 0 ? Ocorrencias::find()->where(["codigo" => $Frequencias[0]->codigoOcorrencia])->one()->ocorrencia : "").'<!-- Assunto--></td>
                <td align="center">'.($countFrequencias > 0 ? date("d/m/Y",strtotime($Frequencias[0]->dataInicial))." a ".date("d/m/Y",strtotime($Frequencias[0]->dataFinal)) : "").'<!-- Periodo da Ocorrencia--></td> 
            </tr>
            ');

            if($countFrequencias>1){
                for ($i = 1; $i < $countFrequencias; $i++){
                    $pdf->WriteHtml
                    ('
                    <tr>
                        <td height="20px">'.$Frequencias[$i]->codigoOcorrencia.'<!-- Codigo--></td>
                        <td style="font-size: 70%;">'.Ocorrencias::find()->where(["codigo" => $Frequencias[$i]->codigoOcorrencia])->one()->ocorrencia.'<!-- Assunto--></td>
                        <td align="center">'.date("d/m/Y",strtotime($Frequencias[$i]->dataInicial))." a ".date("d/m/Y",strtotime($Frequencias[$i]->dataFinal)).'<!-- Periodo da Ocorrencia--></td> 
                    </tr>
                    ');
                }    
            }                
        }

        $pdf->WriteHtml
        ('
        <tr>
            <td colspan="7" height="100px" align="left" style="font-size:65%; vertical-align: top; font-weight: normal;"><b>OBSERVAÇÕES:</b> <u>(1.indique aqui os dados do documento sobre a ocorrência assinalada para o servidor/ 2.Servidor que exerça CD em setor diverso da lotação, indique para localização/ 3.Se não houver qualquer ausência para o servidor, mantenha os dias de pagamento inalterados):</u>
            </td>
        </tr>
        ');
    
        $pdf->WriteHtml
        ('
            </table>
        ');
        
        $pdf->Output('');
        $pdfcode = $pdf->output();
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
