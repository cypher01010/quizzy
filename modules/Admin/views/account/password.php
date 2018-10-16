<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<h2>Update Password</h2>
			<?php if($updated == true) { ?>
				<div class="alert alert-default">
					<button type="button" class="close" data-dismiss="alert">
						<span aria-hidden="true">×</span>
						<span class="sr-only">Close</span>
					</button>
					Password has been updated
				</div>
			<?php } ?>
			<div class="panel-body">
				<?php $form = ActiveForm::begin([
					'id' => 'update-password-form',
					'options' => ['class' => 'form-horizontal register-form'],
					'fieldConfig' => [
						'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
						'labelOptions' => ['class' => 'col-lg-2 control-label'],
					],
				]); ?>

					<div class="form-group">
						<?php echo $form->field($updatePasswordForm, 'old')->passwordInput(); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($updatePasswordForm, 'new')->passwordInput(); ?>
						<?php echo $form->field($updatePasswordForm, 'retype')->passwordInput(); ?>
					</div>

					<div class="form-group">
						<div class="col-lg-offset-1 col-lg-11 registration-button-left">
							<?= Html::submitButton('Update Password', ['class' => 'btn btn-quizzy', 'name' => 'login-button']) ?>
						</div>
					</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>