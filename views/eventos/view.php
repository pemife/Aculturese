<?php

use app\models\Usuarios;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Eventos', 'url' => ['publicos']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$anadirParticipanteUrl = Url::to(['anadir-participante', 'eventoId' => $model->id]);
$borrarParticipanteUrl = Url::to(['borrar-participante', 'eventoId' => $model->id]);
$url2 = Url::to(['lista-participantes', 'eventoId' => $model->id]);
$userId = Yii::$app->user->isGuest ? null : Yii::$app->user->id;
// $userNombre = Yii::$app->user->isGuest ? null : $usuarioLogeado->nombre;
$botonBorrarse = Html::a('Borrarse', '#', [
  'class' => 'btn btn-danger',
  'id' => 'borrarParticipantes',
  'style' => 'display: none;',
]);
$botonAnadirse = Html::a('Unirse', '#', [
  'class' => 'btn btn-success',
  'id' => 'anadirParticipantes',
  'style' => 'display: none;',
]);

$boolAsistente = Usuarios::findOne(Yii::$app->user->id)->esAsistente(Yii::$app->user->id, $model->id);
$boolAsistenteJS = json_encode($boolAsistente);

$js = <<<EOF
$('document').ready(function(){
  actualizarLista();
});

if ($boolAsistenteJS) {
  $("#borrarParticipantes").show();
  $("#anadirParticipantes").hide();
} else {
  $("#borrarParticipantes").hide();
  $("#anadirParticipantes").show();
}

$('#anadirParticipantes').click(function(e){
  if(!$('#asistente$userId').length){
    $.ajax({
      method: 'GET',
      url: '$anadirParticipanteUrl',
      data: {},
        success: function(result){
          if (result) {
            $('#asistentesAjax').html(result);
            $("#borrarParticipantes").show();
            $("#anadirParticipantes").hide();
          } else {
            alert('Ha habido un error con la lista de asistentes');
          }
        }
      });
  } else {
    alert("Ya eres un asistente de este evento!");
  }
});

$('#borrarParticipantes').click(function(e){
  if($('#asistente$userId').length){
    $.ajax({
      method: 'GET',
      url: '$borrarParticipanteUrl',
      data: {},
        success: function(result){
          if (result) {
            $('#asistentesAjax').html(result);
            $("#borrarParticipantes").hide();
            $("#anadirParticipantes").show();
          } else {
            alert('Ha habido un error con la lista de asistentes');
          }
        }
      });
  } else {
    alert("No eres un asistente de este evento");
  }
});

function actualizarLista(){
  $.ajax({
      method: 'GET',
      url: '$url2',
      data: {},
      success: function(result){
          if (result) {
              $('#asistentesAjax').html(result);
          } else {
              alert('Ha habido un error con la lista de asistentes(2)');
          }
      },
      error: function(){
        alert("error en actualizar lista");
      }
    });
}
EOF;
$this->registerJs($js);
?>
<style>
  .flex-container{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  #asistentes-ajax > div {
    flex-grow: 1;
    width: 32%;
    padding: 10px;
  }

  .tabla-asistentes {
    align-self: flex-end;
  }
</style>
<div class="eventos-view">

    <h1><?= Html::encode($this->title) ?>   <span class="<?= $model->es_privado ? "glyphicon glyphicon-lock" : "glyphicon glyphicon-globe" ?>"></span></h1>

      <p>
        <?php if( !Yii::$app->user->isGuest ){ ?>
          <span id="spanBoton">
            <?= $botonBorrarse ?>
            <?= $botonAnadirse ?>
          </span>
        <?php } ?>
        <?php if( Yii::$app->user->id === 1 || Yii::$app->user->id === $model->creador_id ){ ?>
          <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
          <?= Html::a('Borrar Evento', ['delete', 'id' => $model->id], [
              'class' => 'btn btn-danger',
              'data' => [
                  'confirm' => '¿Seguro que quieres borrar este evento?',
                  'method' => 'post',
              ],
          ]) ?>
        <?php } ?>
      </p>

    <div class="flex-container">
      <div>
        <!-- <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
            'id',
            'nombre',
            'inicio',
            'fin',
            [
              'label' => 'Lugar',
              'value' => $model->lugar->nombre,
            ],
            [
              'label' => 'Tipo de evento',
              'value' => $model->categoria->nombre,
            ],
            [
              'label' => 'Creado por',
              'value' => Html::a($model->creador->nombre, ['usuarios/view', 'id' => $model->creador->id]),
              'format' => 'html',
            ],
            'es_privado:boolean',
          ],
          ]) ?> -->

        <?= Yii::$app->controller->renderPartial('detalleEvento', [
          'evento' => $model,
          ]) ?>
      </div>
      <div id="asistentesAjax">

      </div>
    </div>


</div>
