<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>
<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')) { ?>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-body" style="padding-top: 0px;">
					<h3 class="text-center">
						Submitted Successfully! You will now be directed back to our homepage.
					</h3>
					<div class="alert alert-success">
						Thank you for contacting us.
						<br />
						We will reply you within 3 business days.
						<br />
						Thank you for your interest in Quizzy.
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(window).load(function () {
		window.setTimeout(function () {
			window.location.href = "<?php echo \Yii::$app->getUrlManager()->createUrl('site/index'); ?>";
		}, 5000);
	});
	</script>
<?php } else { ?>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-body" style="padding-top: 0px;">
					Please fill in the form below stating your enquiry.
					<br />
					We will reply you within 3 business days.
					<br />
					Thank you for your interest in Quizzy.
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-body" style="padding-top: 0px;">

					<?php $form = ActiveForm::begin([
						'id' => 'contact-form',
						'options' => ['class' => 'form-horizontal quizzy-form'],
						'fieldConfig' => [
							'template' => "{label}\n{input}\n{error}",
							'labelOptions' => ['class' => 'control-label'],
						],
					]); ?>

						<?php echo $form->field($model, 'salutation')->dropDownList($salutation); ?>
						<?php echo $form->field($model, 'name'); ?>
						<?php echo $form->field($model, 'type')->dropDownList($type); ?>
						<?php echo $form->field($model, 'email'); ?>
						<?php echo $form->field($model, 'number'); ?>
						<?php echo $form->field($model, 'enquiry')->textArea(['rows' => 6]); ?>

						<?php echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [
							'captchaAction' => '/site/captcha',
							'template' => '<div class="row captcha-input"><div class="col-lg-2">{image}</div><div class="col-lg-3">{input}</div></div>',
						]); ?>

						<div class="accept-terms">
							<?php
								echo $form->field($model, 'accept')->checkboxList([
									'selected' => 'By submitting this enquiry, you acknowledge that you have read and accepted and permit us to use your personal information provided in accordance with our privacy terms. Click <a class="privacy-link-contact-us" href="' . Yii::$app->getUrlManager()->createUrl('page/default/privacy') . '">here</a> to view our Privacy Terms.'
								]);
							?>
						</div>

						<div class="form-group submit-btns">
							<div class="col-md-1" style="margin-right: 30px;">
								<?php echo Html::submitButton('Submit', ['class' => 'btn btn-quizzy', 'name' => 'contact-button']); ?>
							</div>
							<div class="col-md-2">
								<?php echo Html::a('Clear', 'javascript:clear();', [
									'class' => 'btn btn-primary clear-input',
								]); ?>
							</div>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<script type="text/javascript">
function clear()
{
	$('#contactform-salutation').val('');
	$('#contactform-name').val('');
	$('#contactform-type').val('');
	$('#contactform-email').val('');
	$('#contactform-number').val('');
	$('#contactform-enquiry').val('');
	$('#contactform-verifycode').val('');
	$('checkbox[name="ContactForm[accept][]"]').attr("checked", false);
}
</script>