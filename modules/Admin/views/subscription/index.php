<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subscriptions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Subscription', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],

			[
				'header' => 'Name',
				'format' => 'raw',
				'value' => function($data) {
					return $data->name;
				},
				'contentOptions' => ['style' => 'width: 200px;']
			],
			[
				'header' => 'Price ( ' . \Yii::$app->controller->settings['paypal']['currency'] . '$ Currency )',
				'format' => 'raw',
				'value' => function($data) {
					if($data->price == -1) {
						return 'Free ( -1 )';
					} else {
						return \Yii::$app->controller->settings['paypal']['currency'] . '$ ' . $data->price;
					}
				},
				'contentOptions' => ['style' => 'width: 250px;']
			],
			[
				'header' => 'Duration ( Days )',
				'format' => 'raw',
				'value' => function($data) {
					if($data->duration_days == -1) {
						return 'Forever ( -1 )';
					} else {
						return $data->duration_days . ' Day(s)';
					}
				},
				'contentOptions' => ['style' => 'width: 250px;']
			],
			[
				'header' => "Status",
				'format' => 'raw',
				'value' => function($data) {
					return ucfirst(strtolower($data->status));
				},
				'contentOptions' => ['style' => 'width: 100px;']
			],
			[
				'header' => '',
				'format' => 'raw',
				'value' => function($data) {
					$edit = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/subscription/update', 'id' => $data->id]) . '"  title="Edit"><span class="fa fa-edit"></span></a>';

					return $edit;
				},
				'options' => array('width' => 70),
			],
		],
	]); ?>

</div>
