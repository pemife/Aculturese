<?php

use yii\helpers\Html;

?>

<h1><?= Html::encode($model->nombre) ?></h1>

<?= Html::img($model->urlImagen, ['height' => 100, 'width' => 200]) ?>
