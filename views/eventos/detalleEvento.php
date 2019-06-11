<?php
use yii\helpers\Html;
?>
<div class="detalleEvento">
  <?= Html::img($evento->urlImagen, ['height' => 150, 'width' => 275]) ?>
  <p>
    <?= Yii::$app->formatter->asDate($evento->inicio, 'full') ?>
    <br>
    <?= Yii::$app->formatter->asTime($evento->inicio, 'H:mm') ?>
  </p>
  <p>Creado por: <b><?= $evento->creador->nombre ?></b></p>
  <p>Categoria: <?= $evento->categoria->nombre ?></p>
</div>
