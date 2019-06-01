<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Eventos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="eventos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if( Yii::$app->user->id === 1 || Yii::$app->user->id === $model->creador_id ){ ?>
      <p>
          <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
          <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
              'class' => 'btn btn-danger',
              'data' => [
                  'confirm' => '¿Seguro que quieres borrar este evento?',
                  'method' => 'post',
              ],
          ]) ?>
      </p>
  <?php } ?>

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
