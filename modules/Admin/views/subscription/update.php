<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Subscription: ' . ' ' . $model->name;
?>
<div class="subscription-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="subscription-form">
		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($updateSubscriptionForm, 'name')->textInput(['maxlength' => 128]) ?>

		<?= $form->field($updateSubscriptionForm, 'price')->textInput() ?>

		<?= $form->field($updateSubscriptionForm, 'duration')->textInput() ?>

		<?= $form->field($updateSubscriptionForm, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => '']) ?>

		<div class="form-group">
			<?= Html::submitButton('Update Subscription', ['class' => 'btn btn-success']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>
</div>