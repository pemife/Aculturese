<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Eventos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="eventos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if( Yii::$app->user->id === 1 ){ ?>
      <p>
        <?= Html::a('Crear Evento', ['create'], ['class' => 'btn btn-success']) ?>
      </p>
    <?php } ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

          'nombre',
          'inicio:DateTime',
          'fin:DateTime',
          [
            'attribute' => 'lugar.nombre',
            'label' => 'Donde',
          ],
          [
            'attribute' => 'categoria.nombre',
            'label' => 'Categoria',
          ],

          ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
