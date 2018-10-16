<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Usernames: ' . ' ' . $model->username;
?>
<div class="usernames-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="usernames-form">

		<?php $form = ActiveForm::begin(); ?>

			<?= $form->field($model, 'username')->textInput(['maxlength' => 128]) ?>

			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>
