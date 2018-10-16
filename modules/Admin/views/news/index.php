<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchNews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create News', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
           // 'title',
            //'content:ntext',
            //'url:url',
            //'date_created',
            [
                'header' => "Title",
                'format' => 'raw',
                'value' => function($data) {
                    return stripslashes($data->title);
                },
                //'contentOptions' => ['style' => 'width: 120px;']
            ],
            //'status',
            [
                'header' => "Status",
                'format' => 'raw',
                'value' => function($data) {
                    if($data->status == 1) {
                        return 'Publish';
                    } else {
                        return 'Private';
                    }
                },
                'contentOptions' => ['style' => 'width: 120px;']
            ],
            [
                'header' => "",
                'format' => 'raw',
                'value' => function($data) {
                    $edit = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/news/update', 'id' => $data->id]) . '"><span class="fa fa-edit"></span></a>';
                    $view = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['news/default/index']) . '" target="_blank"><span class="fa fa-newspaper-o"></span></a>';
                    return $edit . ' | ' . $view;
                },
                'contentOptions' => ['style' => 'width: 70px;']
            ],
        ],
    ]); ?>

</div>
