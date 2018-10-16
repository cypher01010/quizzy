<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<h3>Disable Login Page</h3>
			<div class="panel-body" style="padding-top: 0px;">
				<form role="form" method="post" name="page-content-form" id="page-content-form">
					<div class="form-group">
						<strong>Allow Login</strong>
						<select class="form-control" id="allow-login" name="allow-login">
							<?php foreach ($loginSelect as $key => $value) { ?>
								<?php if($allowLogin == 1 && $key == 1) { ?>
									<option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
								<?php } else if($allowLogin == 0 && $key == 0) { ?>
									<option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
								<?php } else { ?>
									<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<strong>Message</strong>
						<textarea class="form-control ckeditor" rows="10" name="page-content" id="page-content">
							<?php echo stripslashes($page['content']); ?>
						</textarea>
					</div>
					<div class="form-group">
						<div class="alert alert-default">
							<p>NOTE: When disable the login page, you may use this login page <strong><?php echo Yii::$app->urlManager->createAbsoluteUrl(['admin/user/login']); ?></strong></p>
							<p>Only Admin and Superadmin able to login in the system.</p>
						</div>
					</div>
					<div class="form-group">
						<?php echo Html::submitButton('Update', ['class' => 'btn btn-quizzy', 'name' => 'update-page-content']) ?>
					</div>
					<input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>