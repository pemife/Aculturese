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
          <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
          <?= Html::a('Delete', ['delete', 'id' => $model->id], [
              'class' => 'btn btn-danger',
              'data' => [
                  'confirm' => 'Are you sure you want to delete this item?',
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
            'lugar_id',
            'categoria_id',
            'creador_id',
        ],
    ]) ?>

</div>
