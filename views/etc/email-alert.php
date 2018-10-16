<?php if(!empty(Yii::$app->session->get('id')) && Yii::$app->session->get('emailValidated') == 'no') { ?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-success quizzy-top-alert-message">
				<button type="button" class="close" data-dismiss="alert">
					<span aria-hidden="true">×</span>
					<span class="sr-only">Close</span>
				</button>
				Your email address is not validated. Click <a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['/user/activate/validateemail', 'username' => \Yii::$app->session->get('username')]); ?>"><strong>here</strong></a> to validate.
				<br />
				Did not receive the activation key? Click <a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['/user/activate/resendkey', 'username' => \Yii::$app->session->get('username')]); ?>"><strong>here</strong></a> to resend.
			</div>
		</div>
	</div>
<?php } ?>