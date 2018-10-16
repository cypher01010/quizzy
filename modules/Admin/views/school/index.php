<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SchoolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Schools';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="school-index admin-sets-list">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Create School', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],

			//'id',
			'name',
			'selectable',

			//['class' => 'yii\grid\ActionColumn'],
			[
				'header' => '',
				'format' => 'raw',
				'value' => function($data) {
					$view = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/school/view', 'id' => $data->id]) . '" title="View"><span class="fa fa-eye"></span></a>';
					$edit = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/school/update', 'id' => $data->id]) . '"  title="Edit"><span class="fa fa-edit"></span></a>';
					return $view . ' | ' . $edit; // . ' | ' . $trash;
				},
				'options' => array('width' => 70),
			],
		],
	]); ?>

</div>

<script type="text/javascript">
$( document ).ready(function() {
	$('.school-index table > thead > tr:first th').css('padding-left', '0px').css('padding-bottom', '0px');
});
</script>