<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="eventos-form">

  <?php $form = ActiveForm::begin(); ?>

  <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

  <?= $form->field($model, 'inicio')->widget(DateTimePicker::classname(), [
    'name' => 'Inicio',
    'model' => $model,
    'attribute' => $model->inicio
  ]); ?>

  <?= $form->field($model, 'fin')->widget(DateTimePicker::classname(), [
    'name' => 'Fin',
    'model' => $model,
    'attribute' => $model->fin
  ]); ?>

  <?= $form->field($model, 'lugar_id')->widget(Select2::classname(), [
      'data' => $listaLugares,
      'options' => ['placeholder' => 'Selecciona un lugar...'],
      'pluginOptions' => [
         'allowClear' => true,
      ],
    ]);
  ?>

  <?= Html::button('Lugar nuevo', ['value' => Url::to(['/lugares/create']), 'id' => 'modalButton'], [
    'class' => 'btn btn-primary',
    ]) ?>

  <?php

    Modal::begin([
      'header' => '<h2>Crear lugar</h2>',
      'id' => 'modal',
      'size' => 'modal-lg',
    ]);

    ?>
    <div id="modalContenido">
    </div>
    <?php

    Modal::end();

  ?>
  <!-- TODO: Quiero conseguir que se pueda crear con un modal donde aparezcan
  los Lugares señalados con marcas en maps, y que permita añadir una marca
  nueva para un lugar nuevo creado -->

  <br><br>

  <?= $form->field($model, 'categoria_id')->widget(Select2::classname(), [
      'data' => $listaCategorias,
      'options' => ['placeholder' => 'Selecciona una categoria...'],
      'pluginOptions' => [
          'allowClear' => true,
      ],
    ]);
  ?>

  <?= $form->field($model, 'creador_id')->hiddenInput([
          'readonly' => true,
          'value' => Yii::$app->user->identity->id,
  ])->label(false);
  ?>

  <?= $form->field($model, 'imagen')->fileInput() ?>

  <?= $form->field($model, 'es_privado')->dropDownList([false => 'Publico', true => 'Privado'])->label('Privacidad') ?>

  <div class="form-group">
      <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
  </div>

  <?php ActiveForm::end(); ?>
</div>
