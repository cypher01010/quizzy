<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SetLanguage */

$this->title = 'Create Set Language';
$this->params['breadcrumbs'][] = ['label' => 'Set Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="set-language-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
