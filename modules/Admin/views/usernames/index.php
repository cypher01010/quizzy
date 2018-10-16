<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Usernames';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-usernames-list admin-sets-list">

	<h1>Username List</h1>
	<p>Below are the list of forbiden usernames to use upon users registration</p>

	<p>
		<?= Html::a('Create Usernames', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],
			//'id',
			'username',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>
<script type="text/javascript">
$( document ).ready(function() {
	$('.admin-usernames-list table > thead > tr:first th').css('padding-left', '0px').css('padding-bottom', '0px');
});
</script>