<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\Usuarios;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = "Perfil de " . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$puedeModificar = (Yii::$app->user->id === 1 || Yii::$app->user->id === $model->id);
$enlaceMod = $puedeModificar ? Url::to(['usuarios/update', 'id' => $model->id]) : '#';
$enlaceBor = $puedeModificar ? Url::to(['usuarios/delete', 'id' => $model->id]) : '#';
$enlacePass = $puedeModificar ? Url::to(['usuarios/cambio-pass', 'id' => $model->id]) : '#';

$url2 = Url::to(['lista-amigos', 'usuarioId' => $model->id]);

// La lista de amigos solo son visibles para los amigos o para el propio usuario
$esAmigo = $model->esAmigo(Yii::$app->user->id, $model->id);
$puedeVerAmigos = $esAmigo || (Yii::$app->user->id == $model->id);
$puedeVerAmigosJS = json_encode($puedeVerAmigos);

$js = <<<EOF
$('document').ready(function(){
  actualizarLista();
});

var esAmigo = $puedeVerAmigosJS;

$('#botonAmistad').click(function(e){
  let mensaje = esAmigo ? "¿Estas seguro de borrar como amigo?" : "¿Estas seguro de añadir como amigo?";
  if(confirm(mensaje)){
    actualizarLista();
  } else {
    e.preventDefault();
  }
});

function actualizarLista(){
  if(esAmigo){
    $.ajax({
      method: 'GET',
      url: '$url2',
      data: {},
        success: function(result){
          if (result) {
            $('#amigosAjax').html(result);
          } else {
            alert('Ha habido un error con la lista de asistentes(2)');
          }
        }
      });
  }
}

EOF;
$this->registerJs($js);

?>
<style>
  .nombreOpciones{
    display: inline-flex;
    justify-content: space-between;
    width: 100%;
  }

  .opciones{
    margin-top: 30px;
  }

  .titulo{
    display: inline-flex;
    justify-content: space-between;
  }

  .flex-container{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .flex-container > div {
    flex-grow: 1;
    width: 32%;
    padding: 10px;
  }
</style>

<div class="usuarios-view">
  <div class="nombreOpciones">
    <div class="titulo">
      <h1>
        <?= Html::encode($model->nombre) ?>
      </h1>
      <p>&nbsp;&nbsp;&nbsp;</p>
      <div class="opciones">
        <?php
        if(!Yii::$app->user->isGuest && (Yii::$app->user->id !== $model->id)){

          if($model->esAmigo(Yii::$app->user->id, $model->id)){
            echo Html::a('', ['borrar-amigo', 'amigoId' => $model->id], ['id' => "botonAmistad", 'class' =>'glyphicon glyphicon-remove']);
          } else {
            echo Html::a('', ['mandar-peticion', 'amigoId' => $model->id], ['id' => "botonAmistad", 'class' => 'glyphicon glyphicon-plus']);
          }
        }
        ?>
      </div>
    </div>
    <div class="opciones">
      <span class="dropdown">
        <button class="glyphicon glyphicon-cog" type="button" data-toggle="dropdown" style="height: 30px; width: 30px;"></button>
        <ul class="dropdown-menu pull-right">
            <li>
              <?= Html::a('Modificar perfil', $enlaceMod, [
                  'class' => 'btn btn-link',
                  'disabled' => !$puedeModificar,
                ]) ?>
            </li>
            <li>
              <?= Html::a('Borrar perfil', $enlaceBor, [
                'class' => 'btn btn-link',
                'disabled' => !$puedeModificar,
                'data' => $puedeModificar ?
                  [
                    'confirm' => 'Seguro que quieres borrar el perfil?',
                    'method' => 'post',
                  ] :
                  [],
              ]) ?>
            </li>
            <li>
              <?= Html::a('Cambiar contraseña', $enlacePass, [
                  'class' => 'btn btn-link',
                  'disabled' => !$puedeModificar,
                  'data-method' => 'POST',
                  'data-params' => [
                    'tokenUsuario' => $model->token,
                  ],
                ]) ?>
            </li>
          </ul>
        </span>
      </div>
  </div>
  <div class="flex-container">
    <div>
      <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          'nombre',
          'created_at:RelativeTime',
          'email:email',
          'biografia',
        ],
        ]) ?>
    </div>
    <div id="amigosAjax">

    </div>
  </div>

  <div>
    <h1>Tus eventos:</h1>
    <div class="flex-container">
      <?php
      foreach ($eventosUsuario as $evento) {
        if(Yii::$app->user->id !== $model->id && $evento->es_privado){
          continue;
        }
        ?>
        <div>
          <h2>
            <?= Html::a(
              Html::encode($evento->nombre),
              Url::to(['eventos/view', 'id' => $evento->id])
            )  ?>
          </h2>

          <?= Html::a(
            Html::img($evento->urlImagen, ['height' => 100, 'width' => 200]),
            Url::to(['eventos/view', 'id' => $evento->id])
          ) ?>
          <span>
            <?= Html::encode($evento->inicio) ?><br>
            <span class="<?= $evento->es_privado ? 'glyphicon glyphicon-lock' : 'glyphicon glyphicon-globe' ?>"></span>
          </span>

        </div>
        <?php
      }
      ?>
    </div>
  </div>
</div>
