<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row" style="margin-bottom: 70px;">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<?php $form = ActiveForm::begin([
					'id' => 'email-activate-form',
					'options' => ['class' => 'form-horizontal quizzy-form'],
					'fieldConfig' => [
						'template' => "{label}\n{input}",
						'labelOptions' => ['class' => 'control-label'],
					],
				]); ?>

				<?php echo $form->field($emailActivateForm, 'key') ?>

				<div class="form-group">
					<?php echo Html::submitButton('Validate Email', ['class' => 'btn btn-quizzy', 'name' => 'email-activate-btn']) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>