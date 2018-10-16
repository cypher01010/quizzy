<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="folder-create">
	<h1>Create Folder</h1>
	<div class="folder-form">
		<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($model, 'name')->textInput(['maxlength' => 512]) ?>
			<?php echo $form->field($model, 'description')->textArea(['rows' => 6]); ?>

			<?php echo $form->field($model, 'subscription_id')->dropDownList($subscription); ?>

			<div class="form-group">
				<?= Html::submitButton('Create', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>