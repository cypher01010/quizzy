<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<h3>Voice RSS Settings</h3>
			<div class="panel-body">
				<?php $form = ActiveForm::begin([
					'id' => 'voicerss-form',
					'options' => ['class' => 'form-horizontal register-form'],
					'fieldConfig' => [
						'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
						'labelOptions' => ['class' => 'col-lg-2 control-label'],
					],
				]); ?>

					<div class="form-group">
						<?php echo $form->field($voiceRSSForm, 'key'); ?>
					</div>

					<div class="form-group">
						<?php echo $form->field($voiceRSSForm, 'codec'); ?>
					</div>
					<hr />

					<div class="form-group">
						<div class="col-lg-offset-1 col-lg-11 registration-button-left">
							<?php echo Html::submitButton('Update', ['class' => 'btn btn-quizzy', 'name' => 'update-button']) ?>
						</div>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>