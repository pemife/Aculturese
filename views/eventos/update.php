<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */

$this->title = 'Modificar evento "' . $model->nombre . '"';
$this->params['breadcrumbs'][] = ['label' => 'Eventos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="eventos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
      'listaCategorias' => $listaCategorias,
      'listaLugares' => $listaLugares,
      'model' => $model,
    ]) ?>

</div>
