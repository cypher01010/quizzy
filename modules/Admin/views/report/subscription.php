<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserPurchaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Purchases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-purchase-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],
			//'id',
			'user.full_name',
			//'user_id',
			//'keyword',
			//'date_created',
			// 'date_expired',
			// 'purchase_keyword',
			// 'duration',
			'folder.name',
			//'folder_id',
			'subscription.name',
			//'subscription_package',
			// 'purchase_type',
			//'status',
			'transaction_key:ntext',
			//'amount',
			[
				'header' => 'Amount',
				'format' => 'raw',
				'value' => function($data) {
					return 'SGD$ ' . $data->amount;
				}
			],
			[
				'header' => 'Amount',
				'format' => 'raw',
				'value' => function($data) {
					if($data->date_completed == '') {
						return '-';
					}

					return date(Yii::$app->params['dateFormat']['display'], strtotime($data->date_completed));
					//return $data->date_completed;
				}
			],
			//'date_completed',
			//['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>