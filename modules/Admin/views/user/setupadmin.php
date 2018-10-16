<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="row" style="margin-bottom: 70px;">
	<div class="col-md-12">
		<div class="panel panel-default">
			<h3>Create Admin Account</h3>
			<hr />
			<div class="panel-body" style="padding-top: 0px;">
				<?php $form = ActiveForm::begin([
					'id' => 'login-form',
					'options' => ['class' => 'form-horizontal quizzy-form'],
					'fieldConfig' => [
						'template' => "{label}\n{input}",
						'labelOptions' => ['class' => 'control-label'],
					],
				]); ?>

					<?php echo $form->field($setupAdminForm, 'username'); ?>
					<?php echo $form->field($setupAdminForm, 'email'); ?>
					<?php echo $form->field($setupAdminForm, 'password'); ?>

					<div class="form-group">
						<div class="student-ids-hidden"></div>
						<?php echo Html::submitButton('Create Admin Account', ['class' => 'btn btn-quizzy', 'name' => 'login-button']) ?>
					</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>