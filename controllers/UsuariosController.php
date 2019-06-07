<?php

namespace app\controllers;

use app\models\Eventos;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
              'class' => AccessControl::classname(),
              'only' => ['update', 'login', 'logout'],
              'rules' => [
                [
                  'allow' => true,
                  'actions' => ['update'],
                  'roles' => ['@'],
                  /*'matchCallback' => function ($rule, $action) {
                      return Yii::$app->user->id === 1;
                  },*/
                ],
                [
                  'allow' => true,
                  'actions' => ['login'],
                  'roles' => ['?'],
                ],
                [
                  'allow' => true,
                  'actions' => ['logout'],
                  'roles' => ['@'],
                ],
              ],
            ],
        ];
    }

    /**
     * Lists all Usuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuarios model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $eventosUsuario = Eventos::findBySql('select e.* from eventos e join usuarios_eventos ue on e.id=ue.evento_id join usuarios u on ue.usuario_id=u.id where u.id=' . $model->id)->all();
        //$eventosUsuario = Eventos::find()->with('usuarios')->all();

        return $this->render('view', [
            'model' => $model,
            'eventosUsuario' => $eventosUsuario,
        ]);
    }

    /**
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuarios();

        $model->scenario = Usuarios::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post())) {
            $model->token = $model->creaToken();
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Usuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->tienePermisos($model)) {
            $model->scenario = Usuarios::SCENARIO_UPDATE;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            $model->password = '';

            return $this->render('update', [
              'model' => $model,
            ]);
        }
        Yii::$app->session->setFlash('danger', 'No puedes modificar el perfil de otra persona');
        return $this->goHome();
    }

    /**
     * Deletes an existing Usuarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($this->tienePermisos($model)) {
            $model->delete();

            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('danger', 'No puedes borrar el perfil de otra persona');
        return $this->goHome();
    }

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRecupass()
    {
        if ($email = Yii::$app->request->post('email')) {
            // Si el email esta vinculado con un usuario
            if ($model = Usuarios::find()->where(['email' => $email])->one()) {
                Yii::$app->mailer->compose()
                ->setFrom('aculturese@gmail.com')
                ->setTo($email)
                ->setSubject('Recuperacion de contraseña')
                ->setHtmlBody('Para recuperar la contraseña, pulsa '
                . Html::a('aqui', Url::to(['usuarios/cambio-pass', 'id' => $model->id], true), [
                  'data-method' => 'POST', 'data-params' => [
                    'tokenUsuario' => $model->token,
                  ],
                ]))
                ->send();
                Yii::$app->session->setFlash('info', 'Se ha mandado el email');
            } else {
                Yii::$app->session->setFlash('error', 'No se ha encontrado una cuenta vinculada a ese email');
            }
            return $this->redirect(['site/login']);
        }
        $email = '';
        return $this->render('escribeMail');
    }
    public function actionCambioPass($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post('tokenUsuario') !== $model->token) {
            Yii::$app->session->setFlash('error', 'Validación incorrecta de usuario');
            return $this->redirect(['site/login']);
        }
        $model->scenario = Usuarios::SCENARIO_UPDATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', 'La contraseña se ha guardado correctamente');
            return $this->redirect(['site/login']);
        }
        $model->password = $model->password_repeat = '';
        return $this->render('cambioPass', [
            'model' => $model,
        ]);
    }

    public function actionOlvideNick()
    {
        if (Yii::$app->request->post('email')) {
            $model = Usuarios::find()->where(['email' => Yii::$app->request->post('email')])->one();
            if ($model) {
                Yii::$app->session->setFlash('info', 'Tu nick es: ' . $model->nombre);
                return $this->redirect(['site/login']);
            }
            Yii::$app->session->setFlash('error', 'El email que ha insertado no tiene ningun usuario asignado, pulse ' . Html::a('aqui', ['usuarios/create']) . ' para registrarse.');
            return $this->redirect(['site/login']);
        }

        $email = '';
        return $this->render('escribeMail', [
          'email' => $email,
        ]);
    }

    public function actionListaAmigos($usuarioId)
    {
        return $this->renderAjax('vistaAmigos', [
          'listaAmigos' => $this->findModel($usuarioId)->amigos,
        ]);
    }

    public function tienePermisos($model)
    {
        return Yii::$app->user->id === 1 || Yii::$app->user->id === $model->id;
    }
}
