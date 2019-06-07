<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

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

$js = <<<EOF
$('document').ready(function(){
  actualizarLista();
});

$('#botonAmistad').click(function(e){
  actualizarLista();
});

function actualizarLista(){
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
            echo Html::a('', ['borrar-amigo', 'id' => $model->id], ['id' => "botonAmistad", 'class' =>'glyphicon glyphicon-remove']);
          } else {
            echo Html::a('', ['anadir-amigo', 'id' => $model->id], ['id' => "botonAmistad", 'class' => 'glyphicon glyphicon-plus']);
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
      <table class="table table-striped table bordered">
        <tr>
          <th>Amigos: (<?= count($model->amigos) ?>)</th>
        </tr>
        <?php foreach ($model->amigos as $amigo) { ?>
            <tr>
              <td id="usuario<?= $amigo->id ?>">
                <?= Html::a(Html::encode($amigo->nombre), ['view', 'id' => $amigo->id]) ?>
              </td>
            </tr>
        <?php } ?>
      </table>
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
