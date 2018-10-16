<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->id;
?>
<div class="usernames-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Create Usernames', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'username',
		],
	]) ?>

	 <p>
		<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Delete', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'Are you sure you want to delete this item?',
				'method' => 'post',
			],
		]) ?>
	</p>
</div>
