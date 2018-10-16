<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<h3>Disable Registration Page</h3>
			<div class="panel-body" style="padding-top: 0px;">
				<form role="form" method="post" name="page-content-form" id="page-content-form">
					<div class="form-group">
						<strong>Allow Register</strong>
						<select class="form-control" id="allow-register" name="allow-register">
							<?php foreach ($registerSelect as $key => $value) { ?>
								<?php if($allowRegister == 1 && $key == 1) { ?>
									<option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
								<?php } else if($allowRegister == 0 && $key == 0) { ?>
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
						<?php echo Html::submitButton('Update', ['class' => 'btn btn-quizzy', 'name' => 'update-page-content']) ?>
					</div>
					<input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>