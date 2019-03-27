<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lugares */

$this->title = 'Update Lugares: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lugares', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lugares-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
