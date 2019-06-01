<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Escribe email';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

  <h1><?= Html::encode($this->title) ?></h1>

  <?= Html::beginForm(); ?>

  <?= Html::label('Email', 'email') ?>
  <?= Html::textInput('email', '', ['class' => 'form-control']) ?>

  <p>
    &nbsp;&nbsp;&nbsp;&nbsp;
  </p>

  <div class="form-group">
      <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
  </div>

  <?php Html::endForm() ?>

</div>
