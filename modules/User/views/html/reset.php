<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="row">	
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<p>Reset Password for <?php echo $username ?></p>
			</div>
		</div>
	</div>
</div>
<div class="row">	
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<?php $form = ActiveForm::begin([
					'id' => 'reset-form',
					'options' => ['class' => 'form-horizontal quizzy-form'],
					'fieldConfig' => [
						'template' => "{label}\n{input}\n{error}",
						'labelOptions' => ['class' => 'control-label'],
					],
				]); ?>

				<?php echo $form->field($resetForm, 'new_password')->passwordInput(); ?>
				<?php echo $form->field($resetForm, 'retype_password')->passwordInput(); ?>

				<div class="form-group">
					<?php echo Html::submitButton('Save password', ['class' => 'btn btn-quizzy', 'name' => 'reset-button']) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>