<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Folders';
?>
<div class="admin-folder-list admin-sets-list">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?= Html::a('New Folder', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			'name',
			[
				'header' => 'Set Listed',
				'format' => 'raw',
				'value' => function($data) {
					$html = '';

					$setList = \Yii::$app->controller->folderSetList($data->id);
					if(count($setList) > 0) {
						foreach ($setList as $key => $set) {
							$html .= '<div>';
								$html .= '<a class="set-folder-listed" href="' . \Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $set['id']]) . '" title="View">' . stripslashes($set['title']) . '</a>';
							$html .= '</div>';
						}
					}

					return $html;
				},
				'options' => array('width' => 200),
			],
			[
				'header' => 'Subscription',
				'format' => 'raw',
				'value' => function($data) {
					return \Yii::$app->controller->getSubscriptionInfo($data->subscription_id);
				},
				'options' => array('width' => 200),
			],
			[
				'header' => '',
				'format' => 'raw',
				'value' => function($data) {
					$view = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/folders/view', 'id' => $data->id]) . '" title="View"><span class="fa fa-eye"></span></a>';
					$edit = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/folders/update', 'id' => $data->id]) . '"  title="Edit"><span class="fa fa-edit"></span></a>';
					return $view . ' | ' . $edit;
				},
				'options' => array('width' => 70),
			],
		],
	]); ?>
</div>
<script type="text/javascript">
$( document ).ready(function() {
	$('.admin-folder-list table > thead > tr:first th').css('padding-left', '0px').css('padding-bottom', '0px');
});
</script>