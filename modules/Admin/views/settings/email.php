<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
	<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<?php $form = ActiveForm::begin([
					'id' => 'mail-settings-form',
					'options' => ['class' => 'form-horizontal quizzy-form'],
					'fieldConfig' => [
						'template' => "{label}\n{input}",
						'labelOptions' => ['class' => 'control-label'],
					],
				]); ?>


				<h4>Global Sender</h4><hr />
				<?php echo $form->field($settingsForm, 'sender'); ?>
				<br /><br />


				<h4>Contact Us Receiver</h4><hr />
				<?php echo $form->field($settingsForm, 'contact'); ?>
				<br /><br />


				<h4>SMTP Settings</h4><hr />
				<?php echo $form->field($settingsForm, 'host'); ?>
				<?php echo $form->field($settingsForm, 'port'); ?>
				<?php echo $form->field($settingsForm, 'username'); ?>
				<?php echo $form->field($settingsForm, 'password'); ?>
				<?php echo $form->field($settingsForm, 'encryption'); ?>
				<br /><br />


				<h4>Test Email Receiver</h4><hr />
				<?php echo $form->field($settingsForm, 'testReceiver'); ?>
				<p>The email address receiver for testing the new SMTP settings</p>

				<br /><br />
				<div class="form-group">
					<p>NOTE: Before you hit the button <strong>'Update'</strong> test the new settings first!</p>
				</div>

				

				<div class="form-group">
					<?php echo Html::submitButton('Update', ['class' => 'btn btn-quizzy', 'name' => 'update-button']) ?>
					<span style="padding-right: 30px;"></span>
					<a href="javascript:;" class="test-smtp-settings">Test SMTP Settings</a>
				</div>

				<?php ActiveForm::end(); ?>
				</div>
		</div>

</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$(document).on('click', 'a.test-smtp-settings', function() {
		var host = $('#emailform-host').val();
		var port = $('#emailform-port').val();
		var username = $('#emailform-username').val();
		var password = $('#emailform-password').val();
		var encryption = $('#emailform-encryption').val();
		var testreceiver = $('#emailform-testreceiver').val();
		var sender = $('#emailform-sender').val();


		jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/settings/testsmtpsettings'); ?>',
			cache : true,
			data : {
				host : host,
				port : port,
				username : username,
				password : password,
				encryption : encryption,
				testreceiver : testreceiver,
				sender : sender,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
					alert('test is done!');
				}
			}
		});
	});
});
</script>