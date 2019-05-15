<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$puedeModificar = (Yii::$app->user->id === 1 || Yii::$app->user->id === $model->id);
$enlaceMod = $puedeModificar ? Url::to(['usuarios/update', 'id' => $model->id]) : '#';
$enlaceBor = $puedeModificar ? Url::to(['usuarios/delete', 'id' => $model->id]) : '#';

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
</style>

<div class="usuarios-view">
  <div class="nombreOpciones">
    <div class="titulo">
      <h1><?= Html::encode($model->nombre) ?></h1>
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
          </ul>
        </span>
      </div>
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
