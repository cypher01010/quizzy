<?php
use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Academic Levels';
?>
<div class="academic-level-index admin-sets-list">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create Academic Level', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],
			//'id',

			'academic',
			'selectable',

			//['class' => 'yii\grid\ActionColumn'],
			[
				'header' => '',
				'format' => 'raw',
				'value' => function($data) {
					$view = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/academic/view', 'id' => $data->id]) . '" title="View"><span class="fa fa-eye"></span></a>';
					$edit = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/academic/update', 'id' => $data->id]) . '"  title="Edit"><span class="fa fa-edit"></span></a>';
					return $view . ' | ' . $edit; // . ' | ' . $trash;
				},
				'options' => array('width' => 70),
			],
		],
	]); ?>

</div>

<script type="text/javascript">
$( document ).ready(function() {
	$('.academic-level-index table > thead > tr:first th').css('padding-left', '0px').css('padding-bottom', '0px');
});
</script>