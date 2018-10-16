<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="panel panel-default">
			<?php if($allowRegister == 0) { ?>
				<?php echo $allowRegisterMessage; ?>
			<?php } else { ?>
				<div class="panel-body">
					<?php $form = ActiveForm::begin([
						'id' => 'login-form',
						'options' => ['class' => 'form-horizontal register-form'],
						'fieldConfig' => [
							'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
							'labelOptions' => ['class' => 'col-lg-2 control-label'],
						],
					]); ?>

						<div class="form-group">
							<?php echo $form->field($registerForm, 'name'); ?>
							<?php echo $form->field($registerForm, 'username'); ?>
							<?php //echo $form->field($registerForm, 'email'); ?>
						</div>

						<div class="form-group">
							<?php echo $form->field($registerForm, 'password')->passwordInput(); ?>
							<?php echo $form->field($registerForm, 'passwordConfirm')->passwordInput(); ?>
						</div>

						<div class="form-group">
							<?php echo $form->field($registerForm, 'birthDay',[
								'template' => "{label}<div class=\"col-lg-5\" style=\"width: 38%;\">{input}</div>\n<div class=\"col-lg-2\">{error}</div>",
							])->textInput([
								'data-format' => 'YYYY-MM-DD',
								'data-template' => 'DD MMMM YYYY',
							]); ?>
						</div>

						<div class="form-group">
							<?php
								echo $form->field($registerForm, 'agree')->checkboxList([
									'selected' => 'I agree to the <a target="_blank" class="agree privacy-link-contact-us" href="' . Yii::$app->getUrlManager()->createUrl('page/default/privacy') . '">Privacy Terms</a>'
								]);
							?>
						</div>

						<div class="form-group">
							<div class="col-lg-offset-1 col-lg-11 registration-button-left">
								<?= Html::submitButton('Register', ['class' => 'btn btn-quizzy', 'name' => 'login-button']) ?>
							</div>
						</div>

					<?php ActiveForm::end(); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#parentregisterform-birthday').combodate();
</script>