<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<h1>
  <?= Html::a( $model->nombre, Url::to(['eventos/view', 'id' => $model->id]))  ?>
</h1>

<?= Html::img($model->urlImagen, ['height' => 100, 'width' => 200]) ?>
