<?php

use kartik\datetime\DateTimePicker;

use yii\bootstrap\Modal;

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Eventos */

$this->title = 'Crear un evento';
$this->params['breadcrumbs'][] = ['label' => 'Eventos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$js = <<<EOF
$('#modalButton').click(function(e){
    $('#modal').modal('show').find('#modalContenido').load($(this).attr('value'));
});
EOF;
$this->registerJs($js);

?>
<div class="eventos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inicio')->widget(DateTimePicker::classname(), [
      'name' => 'Inicio',
      'model' => $model,
      'attribute' => $model->inicio
    ]); ?>
    <!-- html input calendario -->

    <?= $form->field($model, 'fin')->widget(DateTimePicker::classname(), [
      'name' => 'Fin',
      'model' => $model,
      'attribute' => $model->fin
    ]); ?>
    <!-- html input calendario -->

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

    <?= $form->field($model, 'creador_id')->textInput([
            'readonly' => true,
            'value' => Yii::$app->user->identity->id,
    ]);
    ?>

    <?= $form->field($model, 'imagen')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
