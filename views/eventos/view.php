<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Eventos', 'url' => ['publicos']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$url = Url::to(['anadir-participante', 'eventoId' => $model->id]);
$url2 = Url::to(['lista-participantes', 'eventoId' => $model->id]);
$userId = $usuarioLogeado->id;
$userNombre = $usuarioLogeado->nombre;

$js = <<<EOF
$('document').ready(function(){
  actualizarLista();
});

$('#anadirParticipantes').click(function(e){
  if(!$('#asistente$userId').text() == "$userNombre"){
    $.ajax({
      method: 'GET',
      url: '$url',
      data: {},
        success: function(result){
          if (result) {
            $('#asistentesAjax').html(result);
          } else {
            alert('Ha habido un error con la lista de asistentes');
          }
        }
      });
  } else {
    alert("Ya eres un asistente de este evento!");
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

  .flex-container > div {
    width: 32%;
    padding: 10px;
  }

  .tabla-asistentes {
    align-self: flex-end;
  }
</style>
<div class="eventos-view">

    <h1><?= Html::encode($this->title) ?></h1>

      <p>
        <?php if( !Yii::$app->user->isGuest ){ ?>
          <?= Html::a('Unirse', '#', [
            'class' => 'btn btn-success',
            'id' => 'anadirParticipantes',
            ]) ?>
        <?php } ?>
        <?php if( Yii::$app->user->id === 1 || Yii::$app->user->id === $model->creador_id ){ ?>
          <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
          <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
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
        <?= DetailView::widget([
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
          ]) ?>
      </div>
      <div id="asistentesAjax">

      </div>
    </div>


</div>
