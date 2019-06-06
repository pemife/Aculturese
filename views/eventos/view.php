<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Eventos', 'url' => ['publicos']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
          <?= Html::a('Unirse', ['anadir-participante', 'eventoId1' => $model->id], ['class' => 'btn btn-success']) ?>
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
      <div id="tabla-asistentes">
        <table class="table table-striped table bordered">
          <tr>
            <th>Asistentes: (<?= count($listaAsistentes) ?>)</th>
            <?php foreach ($listaAsistentes as $asistente) { ?>
              <tr>
                <td><?= $asistente->nombre ?></td>
              </tr>
            <?php } ?>
          </tr>
        </table>
      </div>
    </div>


</div>
