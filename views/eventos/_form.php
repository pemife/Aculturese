<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="eventos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inicio')->textInput() ?>
    <!-- html input calendario -->

    <?= $form->field($model, 'fin')->textInput() ?>
    <!-- html input calendario -->

    <?= $form->field($model, 'lugar_id')->textInput() ?>
    <!--  kartikk select2 para los lugares existentes, y una opcion para
     crear un lugar nuevo si es necesario -->

    <?= $form->field($model, 'lugar_id')->widget(Select2::classname(), [
        'data' => $listaLugares,
        'options' => ['placeholder' => 'Selecciona una categoria...'],
        'pluginOptions' => [
           'allowClear' => true,
        ],
      ]);
    ?>

    <?= $form->field($model, 'categoria_id')->widget(Select2::classname(), [
        'data' => $listaCategorias,
        'options' => ['placeholder' => 'Selecciona una categoria...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
      ]);
    ?>

    <?= $form->field($model, 'imagen')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
