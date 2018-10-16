<?php
use yii\helpers\Html;
use yii\grid\GridView;
$columns = array();

switch ($type) {
	case \app\models\User::USERTYPE_TRIAL:
	case \app\models\User::USERTYPE_STUDENT:
	case \app\models\User::USERTYPE_TEACHER:
	case \app\models\User::USERTYPE_PARENT:
		$columns = [
			//['class' => 'yii\grid\SerialColumn'],
			[
				'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortId]) . "'>No.</a>",
				'format' => 'raw',
				'value' => function($data) {
					return $data->id;
				},
				'contentOptions' => ['style' => 'width: 65px;']
			],
			'full_name',
			'email:email',
			[
				'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortAge]) . "'>Age</a>",
				'format' => 'raw',
				'value' => function($data) {
					$date = new DateTime($data->birth_day);
					$now = new DateTime();
					$interval = $now->diff($date);

					return $interval->y;
				},
				'contentOptions' => ['style' => 'width: 65px;']
			],
			'current_school',
			[
				'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortAcademicLevel]) . "'>Academic Level</a>",
				'format' => 'raw',
				'value' => function($data) {
					return \Yii::$app->controller->academicLevelListView($data->academic_level);
				},
				'contentOptions' => ['style' => 'width: 100px;']
			],
			[
				'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortDateCreated]) . "'>Date Join</a>",
				'format' => 'raw',
				'value' => function($data) {
					return date(Yii::$app->params['dateFormat']['display'], strtotime($data->date_created));
				},
				'contentOptions' => ['style' => 'width: 110px;']
			],
			[
			'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortStatus]) . "'>Status</a>",
				'format' => 'raw',
				'value' => function($data) {
					return ucfirst(strtolower($data->status));
				},
				'contentOptions' => ['style' => 'width: 100px;']
			],
			/**
			[
				'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortEmailActivated]) . "'>Email Validated</a>",
				'attribute' => 'email_activated:email',
				//'format' => 'raw',
				'filter' => array('yes' => 'Validated', 'no' => 'Not Validated'),
				'value' => function($data) {
					$response = 'Not Validated';
					if($data->email_activated === 'yes') {
						$response = 'Validated';
					}
					if($data->type === \app\models\User::USERTYPE_ADMIN || $data->type === \app\models\User::USERTYPE_SUPER_ADMIN) {
						return '-';
					} else {
						return $response;
					}
				},
			],
			*/
			[
				'header' => "",
				'format' => 'raw',
				'value' => function($data) {
					switch ($data->type) {
						case \app\models\User::USERTYPE_TRIAL:
						case \app\models\User::USERTYPE_STUDENT:
						case \app\models\User::USERTYPE_TEACHER:
							$profile = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $data->username]) . '" title="Public Profile" target="_blank"><span class="fa fa-user"></span></a>';
							$update = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/user/update', 'id' => $data->id]) . '" title="Update Account"><span class="fa fa-edit"></span></a>';
							return $profile . ' | ' . $update;
						case \app\models\User::USERTYPE_PARENT:
							$update = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/user/update', 'id' => $data->id]) . '" title="Update Account"><span class="fa fa-edit"></span></a>';
							return $update;
						case \app\models\User::USERTYPE_ADMIN:
							$update = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/user/update', 'id' => $data->id]) . '" title="Update Account"><span class="fa fa-edit"></span></a>';
							return $update;
						case \app\models\User::USERTYPE_SUPER_ADMIN:
							return '-';
					}
				},
				'contentOptions' => ['style' => 'width: 65px;']
			],
			// ['class' => 'yii\grid\ActionColumn'],
		];
		break;
	case \app\models\User::USERTYPE_ADMIN:
		if(Yii::$app->session->get('type') ===  \app\models\User::USERTYPE_ADMIN) {
			$columns = [
				'username',
				'email:email',
				[
				'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortStatus]) . "'>Status</a>",
					'format' => 'raw',
					'value' => function($data) {
						return ucfirst(strtolower($data->status));
					},
					'contentOptions' => ['style' => 'width: 100px;']
				],
			];
		} else {
			$columns = [
				'username',
				'email:email',
				[
				'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortStatus]) . "'>Status</a>",
					'format' => 'raw',
					'value' => function($data) {
						return ucfirst(strtolower($data->status));
					},
					'contentOptions' => ['style' => 'width: 100px;']
				],
				[
					'header' => "",
					'format' => 'raw',
					'value' => function($data) {
						if(\Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) {
							$update = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/user/update', 'id' => $data->id]) . '" title="Update Account"><span class="fa fa-edit"></span></a>';
							return $update;
						}
					},
					'contentOptions' => ['style' => 'width: 65px;']
				],
			];
		}
		break;
	case \app\models\User::USERTYPE_SUPER_ADMIN:
		$columns = [
			'username',
			'email:email',
			[
			'header' => "<a href='" . \Yii::$app->getUrlManager()->createUrl(['admin/user/list', 'type' => $type, 'sort' => $sortStatus]) . "'>Status</a>",
				'format' => 'raw',
				'value' => function($data) {
					return ucfirst(strtolower($data->status));
				},
				'contentOptions' => ['style' => 'width: 100px;']
			],
		];
		break;
}
?>
<?php if($type === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
	<h1><?php echo ucwords(str_replace('-', ' ', $type)) . ' Admin - List'; ?> [ <a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/export', 'type' => $type]); ?>" target="_blank"><i class="fa fa-file-excel-o"></i> Export Users</a> ]</h1>
<?php } else { ?>
	<?php
	$label = ucwords(str_replace('-', ' ', $type));
	if($type == 'student' || $type == 'parent' || $type == 'trial-user' || $type == 'teacher') {
		$label = $label . 's';
	}
	?>
	<h1><?php echo $label . ' - List'; ?> [ <a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['admin/user/export', 'type' => $type]); ?>" target="_blank"><i class="fa fa-file-excel-o"></i> Export Users</a> ]</h1>
<?php } ?> 

<?php if($type === \app\models\User::USERTYPE_TRIAL) { ?>
	<p>
		<?= Html::a('Setup Trial User Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setup', 'type' => 'trial-user']), ['class' => 'btn btn-success']) ?>
	</p>
<?php } else if($type === \app\models\User::USERTYPE_STUDENT) { ?>
	<p>
		<?= Html::a('Setup Student Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setup', 'type' => 'student']), ['class' => 'btn btn-success']) ?>
	</p>
<?php } else if($type === \app\models\User::USERTYPE_TEACHER) { ?>
	<p>
		<?= Html::a('Setup Teacher Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setup', 'type' => 'teacher']), ['class' => 'btn btn-success']) ?>
	</p>
<?php } else if($type === \app\models\User::USERTYPE_PARENT) { ?>
	<p>
		<?= Html::a('Setup Parent Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setparent']), ['class' => 'btn btn-success']) ?>
	</p>
<?php } ?>
<?php if($type === \app\models\User::USERTYPE_ADMIN && Yii::$app->session->get('type') === \app\models\User::USERTYPE_SUPER_ADMIN) { ?>
	<p>
		<?= Html::a('Setup Admin Account', \Yii::$app->getUrlManager()->createUrl(['admin/user/setupadmin']), ['class' => 'btn btn-success']) ?>
	</p>
<?php } ?>
<div class="admin-sets-list">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => $columns,
	]); ?>
</div>