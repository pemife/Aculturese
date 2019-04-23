<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Eventos', 'url' => ['/eventos/index']],
            ['label' => 'Usuarios', 'url' => ['/usuarios/index']],
            ['label' => 'Calendarios (proximamente)', 'url' => ['/usuarios/calendario']],
            Yii::$app->user->isGuest ? (
              '<li>'
              . Html::a('Login', ['site/login'])
              . '</li><li>'
              . Html::a('Registrar', ['usuarios/create'])
              . '</li>'
            ) : (
              '<span class="dropdown" style="margin: 0; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">'
              . '<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">'
              . Yii::$app->user->identity->nombre . '&nbsp;&nbsp;'
              . '<span class="caret"></span></button>'
              . '<ul class="dropdown-menu">'
              . '<li>' . Html::a('Ver perfil', ['usuarios/view', 'id' => Yii::$app->user->id]) . '</li>'
              . '<li>' . Html::a('Modificar perfil', ['usuarios/update', 'id' => Yii::$app->user->id]) . '</li>'
              . Html::beginForm(['/site/logout'], 'post')
              . '<li>' . Html::submitButton(
                          'Logout (' . Yii::$app->user->identity->nombre . ')',
                          ['class' => 'btn btn-link logout'])
              . '</li>'
              . Html::endForm()
              . '</ul>'
              . '</span>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
