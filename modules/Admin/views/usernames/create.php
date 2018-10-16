<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Forbid Username';
?>
<div class="usernames-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="usernames-form">
		<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($model, 'username')->textInput(['maxlength' => 128]) ?>
			<div class="form-group">
				<?= Html::submitButton('Forbid Username', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>