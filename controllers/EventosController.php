<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Eventos;
use app\models\EventosSearch;
use app\models\Lugares;
use app\models\Usuarios;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * EventosController implements the CRUD actions for Eventos model.
 */
class EventosController extends Controller
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
              'only' => ['index'],
              'rules' => [
                [
                  'allow' => false,
                  'actions' => ['index'],
                  'denyCallback' => function ($rule, $action) {
                      return $this->redirect(['eventos/publicos']);
                  },
                ],
              ],
            ],
        ];
    }

    /**
     * Lists all Eventos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPublicos()
    {
        $searchModel = new EventosSearch();
        $query = Eventos::find()->where(['es_privado' => false]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Eventos model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if (!$model->es_privado || $this->tienePermisos($model) || $this->esAsistente($model)) {
            return $this->render('view', [
              'model' => $model,
            ]);
        }
        Yii::$app->session->setFlash('error', 'No tienes acceso a ese evento');
        return $this->redirect('publicos');
    }

    /**
     * Creates a new Eventos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Eventos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $usuario = Usuarios::findOne(Yii::$app->user->id);
            $usuario->asistire($usuario, $model);
            if (UploadedFile::getInstance($model, 'imagen')) {
                $file = 'uploads/' . $model->id . '.jpg';
                $model->imagen = UploadedFile::getInstance($model, 'imagen');
                $model->imagen->saveAs($file);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $model->imagen = null;

            return $this->redirect(['view', 'id' => $model->id]);
        }

        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Debes estar logeado para crear un evento');
            return $this->goBack();
        }

        return $this->render('create', [
            'listaCategorias' => $this->listaCategorias(),
            'listaLugares' => $this->listaLugares(),
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Eventos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'listaCategorias' => $this->listaCategorias(),
            'listaLugares' => $this->listaLugares(),
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Eventos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $evento = $this->findModel($id);
        if ($this->tienePermisos($evento)) {
            $evento->delete();
            return $this->redirect(['index']);
        }
        Yii::$app->session->setFlash('error', 'No puedes borrar el evento sin ser el creador o administrador');

        return $this->goBack();
    }

    /**
     * Finds the Eventos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Eventos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Eventos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function listaCategorias()
    {
        return Categorias::find()
            ->select('nombre')
            ->indexBy('id')
            ->column();
    }

    private function listaLugares()
    {
        return Lugares::find()
            ->select('nombre')
            ->indexBy('id')
            ->column();
    }

    public function esCreador($usuarioId, $evento)
    {
        return $usuarioId === $evento->creador_id;
    }

    public function tienePermisos($evento)
    {
        return Yii::$app->user->id === 1 || $this->esCreador(Yii::$app->user->id, $evento);
    }

    public function esAsistente($evento)
    {
        $asistentes = Usuarios::findBySql('select u.* from eventos e join usuarios_eventos ue on e.id=ue.evento_id join usuarios u on ue.usuario_id=u.id where e.id=' . $evento->id)->all();
        $usuario = Usuarios::findOne(Yii::$app->user->id);
        foreach ($asistentes as $asistente) {
            if ($asistente === $usuario) {
                return true;
            }
        }
        return false;
    }
}
