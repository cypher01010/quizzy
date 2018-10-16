<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Class';
?>
<div class="admin-sets-list">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?= Html::a('Create Class', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			'name',
			 [
				'header' => 'Description',
				'attribute' => 'description',
				'format' => 'raw',
				'value' => function($data) {
					return empty($data->description) ? '[No Description]' : $data->description;
				},
			],
			[
				'header' => '',
				'format' => 'raw',
				'value' => function($data) {
					$view = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/classes/view', 'id' => $data->id]) . '" title="View"><span class="fa fa-eye"></span></a>';
					$edit = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/classes/update', 'id' => $data->id]) . '"  title="Edit"><span class="fa fa-edit"></span></a>';
					return $view . ' | ' . $edit; // . ' | ' . $trash;
				},
				'options' => array('width' => 70),
			],
		],
	]); ?>

</div>
<script type="text/javascript">
$( document ).ready(function() {
	$('.admin-sets-list table > thead > tr:first th').css('padding-left', '0px').css('padding-bottom', '0px');
});
</script>