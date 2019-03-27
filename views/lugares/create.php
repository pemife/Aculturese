<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lugares */

$this->title = 'Create Lugares';
$this->params['breadcrumbs'][] = ['label' => 'Lugares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lugares-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
