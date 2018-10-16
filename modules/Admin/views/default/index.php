<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>
<div class="row">
	<div class="col-sm-3">
		<div class="xe-widget xe-counter xe-counter-info">
			<div class="xe-icon">
				<i class="fa-group"></i>
			</div>
			<div class="xe-label">
				<strong class="num"><?php echo $users['trial-user']; ?></strong>
				<span>Trial Users</span>
			</div>
		</div>
		<div class="xe-widget xe-counter xe-counter-info admin-dashboard-user-buttons">
			<?= Html::a('Setup Trial User Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setup', 'type' => 'trial-user']), ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="xe-widget xe-counter xe-counter-info">
			<div class="xe-icon">
				<i class="fa-group"></i>
			</div>
			<div class="xe-label">
				<strong class="num"><?php echo $users['student']; ?></strong>
				<span>Students</span>
			</div>
		</div>
		<div class="xe-widget xe-counter xe-counter-info admin-dashboard-user-buttons">
			<?= Html::a('Setup Student Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setup', 'type' => 'student']), ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="xe-widget xe-counter xe-counter-info">
			<div class="xe-icon">
				<i class="fa-group"></i>
			</div>
			<div class="xe-label">
				<strong class="num"><?php echo $users['teacher']; ?></strong>
				<span>Teachers</span>
			</div>
		</div>
		<div class="xe-widget xe-counter xe-counter-info admin-dashboard-user-buttons">
			<?= Html::a('Setup Teacher Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setup', 'type' => 'teacher']), ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="xe-widget xe-counter xe-counter-info">
			<div class="xe-icon">
				<i class="fa-group"></i>
			</div>
			<div class="xe-label">
				<strong class="num"><?php echo $users['parent']; ?></strong>
				<span>Parents</span>
			</div>
		</div>
		<div class="xe-widget xe-counter xe-counter-info admin-dashboard-user-buttons" data-count=".num" data-from="1" data-to="117" data-suffix="k" data-duration="3" data-easing="false">
			<?= Html::a('Setup Parent Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setparent']), ['class' => 'btn btn-success']) ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
		<div class="xe-widget xe-counter xe-counter-info">
			<div class="xe-icon">
				<i class="fa-folder-o"></i>
			</div>
			<div class="xe-label">
				<strong class="num"><?php echo $folders; ?></strong>
				<span>Folders</span>
			</div>
		</div>
		<div class="xe-widget xe-counter xe-counter-info admin-dashboard-user-buttons" data-count=".num" data-from="1" data-to="117" data-suffix="k" data-duration="3" data-easing="false">
			<?= Html::a('New Folder', \Yii::$app->getUrlManager()->createUrl(['admin/folders/create']), ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="xe-widget xe-counter xe-counter-info">
			<div class="xe-icon">
				<i class="fa-book"></i>
			</div>
			<div class="xe-label">
				<strong class="num"><?php echo $set; ?></strong>
				<span>Study Set</span>
			</div>
		</div>
		<div class="xe-widget xe-counter xe-counter-info admin-dashboard-user-buttons" data-count=".num" data-from="1" data-to="117" data-suffix="k" data-duration="3" data-easing="false">
			<?= Html::a('New Study Set', \Yii::$app->getUrlManager()->createUrl(['admin/set/add']), ['class' => 'btn btn-success']) ?>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="xe-widget xe-counter xe-counter-info">
			<div class="xe-icon">
				<i class="fa-group"></i>
			</div>
			<div class="xe-label">
				<strong class="num"><?php echo $class; ?></strong>
				<span>Class</span>
			</div>
		</div>
		<div class="xe-widget xe-counter xe-counter-info admin-dashboard-user-buttons" data-count=".num" data-from="1" data-to="117" data-suffix="k" data-duration="3" data-easing="false">
			<?= Html::a('Create Class', \Yii::$app->getUrlManager()->createUrl(['admin/classes/create']), ['class' => 'btn btn-success']) ?>
		</div>
	</div>
</div>
<div class="row">
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
	<?= Html::a('Create Subscription', \Yii::$app->getUrlManager()->createUrl(['admin/subscription/create']), ['class' => 'btn btn-success']) ?>
</div>