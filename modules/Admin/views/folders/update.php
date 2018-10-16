<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$title = 'Update Folder: ' . ' ' . $model->name;
?>
<div class="folder-update">
	<h1><?= Html::encode($title) ?></h1>
	<div class="folder-form">
		<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($model, 'name')->textInput(['maxlength' => 512]) ?>
			<?php echo $form->field($model, 'description')->textArea(['rows' => 6]); ?>

			<?php echo $form->field($model, 'subscription_id')->dropDownList($subscription); ?>

			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>