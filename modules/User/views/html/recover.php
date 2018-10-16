<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="row">	
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<p>Forgot Your Password?</p>
			</div>
		</div>
	</div>
</div>
<div class="row">	
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-body" style="padding-top: 0px;">
				<?php $form = ActiveForm::begin([
					'id' => 'recover-form',
					'options' => ['class' => 'form-horizontal quizzy-form'],
					'fieldConfig' => [
						'template' => "{label}\n{input}",
						'labelOptions' => ['class' => 'control-label'],
					],
				]); ?>

				<?php echo $form->field($recoverForm, 'email')->label('Enter e-mail'); ?>

				<div class="form-group">
					<?php echo Html::submitButton('Email my password', ['class' => 'btn btn-quizzy', 'name' => 'recover-button']) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>