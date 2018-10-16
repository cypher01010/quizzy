<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<?php if(Yii::$app->session->hasFlash('success')){ ?>
			<div class="alert alert-success"><?= Yii::$app->session->getFlash('success'); ?></div>
			<?php } ?>
			<h2>Add New Account</h2>
			<div class="user-form" style="width:50%">
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($userModel, 'username')->textInput(['maxlength' => 64]) ?>
					<?= $form->field($userModel, 'full_name')->textInput(['maxlength' => 512]) ?>
					<?= $form->field($userModel, 'email')->textInput(['maxlength' => 64]) ?>
					<?= $form->field($userModel, 'birth_day')->textInput(['maxlength' => 128, 'placeholder' => 'yyyy-mm-dd']) ?>
					<?= $form->field($userModel, 'fb_account')->textInput(['maxlength' => 64]) ?>
					<?= $form->field($userModel, 'google_account')->textInput(['maxlength' => 64]) ?>
					<?= $form->field($userModel, 'school_type')->dropDownList(ArrayHelper::map($school, 'id', 'name')); ?>
					<?= $form->field($userModel, 'academic_level')->dropDownList(ArrayHelper::map($academicLevel, 'id', 'academic')); ?>
					<?= $form->field($userModel, 'current_school')->textInput(['maxlength' => 512]) ?>
					<?= $form->field($userModel, 'type')->dropDownList(['teacher'=>'Teacher', 'student'=>'Student', 'trial-user' => 'Trial User'], [
						'options' => [
							$type => ['Selected'=>'selected']
						]
					])->label('Account Type'); ?>
					<?= $form->field($userModel, 'status')->dropDownList(['active'=>'Active', 'inactive'=>'Inactive'])->label('Account Status'); ?>
					<div class="form-group">
						<?= Html::submitButton('Add New Account', ['class' => 'btn btn-success']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
	
