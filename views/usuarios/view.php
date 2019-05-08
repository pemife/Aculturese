<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<style>
  .titulo{
    float: left;
  }

  .opciones{
    float: right;
  }
</style>

<div class="usuarios-view">

    <div class="titulo">
      <h1><?= Html::encode($model->nombre) ?></h1>
    </div>

    <div class="opciones">
      <span class="dropdown">
        <button class="glyphicon glyphicon-cog" type="button" data-toggle="dropdown"></button>
        <ul class="dropdown-menu pull-right">
          <?php if(Yii::$app->user->id === 1 || Yii::$app->user->id === $model->id ) { ?>
            <li>
              <?= Html::a('Modificar perfil', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </li>
            <li>
              <?= Html::a('Borrar perfil', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                  'confirm' => 'Are you sure you want to delete this item?',
                  'method' => 'post',
                ],
                ]) ?>
              </li>
            <?php } ?>
          </ul>
        </span>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nombre',
            'created_at:RelativeTime',
            'email:email',
        ],
    ]) ?>

</div>
